<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Dto;

use Buepro\Bexio\Domain\Model\Invoice;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class InvoiceDto
{
    public const REMOTE_PROPERTIES = ['id', 'title', 'document_nr', 'language_id', 'bank_account_id',
        'currency_id', 'total', 'is_valid_from', 'is_valid_to', 'kb_item_status_id', 'reference', 'api_reference',
        'viewed_by_client_at', 'esr_id', 'qr_invoice_id', 'network_link'];

    protected static array $typecastMap = [];
    protected static ?Invoice $dummyInvoice = null;

    public static function getLocalFromRemote(\stdClass $remoteInvoice): Invoice
    {
        $result = new Invoice();
        foreach (self::REMOTE_PROPERTIES as $remoteProperty) {
            $localMethod = 'set' . ucfirst(GeneralUtility::underscoredToLowerCamelCase($remoteProperty));
            $value = self::typecastRemoteValue($remoteProperty, $remoteInvoice->$remoteProperty);
            $result->$localMethod($value);
        }
        return $result;
    }

    public static function update(Invoice $localInvoice, \stdClass $remoteInvoice): bool
    {
        $propertyChanged = false;
        foreach (self::REMOTE_PROPERTIES as $remoteProperty) {
            $ucfirstProperty = ucfirst(GeneralUtility::underscoredToLowerCamelCase($remoteProperty));
            $getter = 'get' . $ucfirstProperty;
            $localValue = $localInvoice->$getter();
            if ($localValue instanceof \DateTime) {
                $debug = 1;
            }
            $remoteValue = self::typecastRemoteValue($remoteProperty, $remoteInvoice->$remoteProperty);
            if (
                (($remoteValue instanceof \DateTime) && ($localValue->getTimestamp() === $remoteValue->getTimestamp())) ||
                $localInvoice->$getter() === $remoteValue
            ) {
                continue;
            }
            $setter = 'set' . $ucfirstProperty;
            $localInvoice->$setter($remoteValue);
            $propertyChanged = true;
        }
        return $propertyChanged;
    }

    protected static function typecastRemoteValue(string $remoteProperty, mixed $value): mixed
    {
        if (self::$dummyInvoice === null) {
            self::$dummyInvoice = new Invoice();
        }
        if (
            !isset(self::$typecastMap[$remoteProperty])
        ) {
            $getter = 'get' . ucfirst(GeneralUtility::underscoredToLowerCamelCase($remoteProperty));
            $type = gettype($dummyValue = self::$dummyInvoice->$getter());
            if ($type === 'object') {
                $type = get_class($dummyValue);
            }
            self::$typecastMap[$remoteProperty] = $type;
        }
        if (self::$typecastMap[$remoteProperty] === \DateTime::class) {
            if (is_string($value)) {
                $value = new \DateTime($value);
            } else {
                $value = (new \DateTime())->setTimestamp(0);
            }
            if ($remoteProperty === 'is_valid_to') {
                $value->setTime(23, 59, 59);
            }
            return $value;
        }
        settype($value, self::$typecastMap[$remoteProperty]);
        return $value;
    }
}
