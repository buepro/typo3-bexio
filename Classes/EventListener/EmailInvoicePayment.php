<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\EventListener;

use Buepro\Bexio\Domain\Model\Invoice;
use Buepro\Bexio\Event\InvoicePaymentEvent;
use Buepro\Bexio\Utility\GeneralUtility as BexioGeneralUtility;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;

class EmailInvoicePayment
{
    public function __invoke(InvoicePaymentEvent $event): void
    {
        $invoice = $event->getInvoice();
        $config = $event->getSite()->getConfiguration()['bexio']['eventListener']['emailInvoicePayment'] ?? [];
        if (($from = MailUtility::getSystemFrom()) === null) {
            $event->requestReprocessing($this->getRequestReprocessingReason('System default sender is not defined'));
            return;
        }
        if (!GeneralUtility::validEmail($email = $config['to']['email'] ?? '')) {
            $event->requestReprocessing($this->getRequestReprocessingReason('Email recipient is not defined'));
            return;
        }
        $to = [$email];
        if (($name = $config['to']['name'] ?? '') !== '') {
            $to[] = $name;
        }
        GeneralUtility::makeInstance(MailMessage::class)
            ->setFrom($from)
            ->to(new \Symfony\Component\Mime\Address(...$to))
            ->subject(sprintf('Bexio invoice %s paid', $invoice->getDocumentNr()))
            ->text($this->getText($invoice))
            ->send();
    }

    private function getRequestReprocessingReason(string $text): string
    {
        return sprintf(
            $text . ' (%s, %d).',
            self::class,
            1654589442
        );
    }

    private function getText(Invoice $invoice): string
    {
        $text = ['Invoice details:'];
        if (($user = $invoice->getUser()) !== null) {
            $text[] = '- Customer: ' . $user->getScreenName();
        }
        $properties = $invoice->toArray();
        unset($properties['user']);
        foreach ($properties as $propertyName => $value) {
            if (($stringValue = BexioGeneralUtility::toString($value)) !== '') {
                $readableName = ucfirst(
                    str_replace('_', ' ', GeneralUtility::camelCaseToLowerCaseUnderscored($propertyName))
                );
                $text[] = sprintf('- %s: %s', $readableName, $stringValue);
            }
        }
        return implode("\n", $text);
    }
}
