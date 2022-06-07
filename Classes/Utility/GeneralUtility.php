<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Utility;

class GeneralUtility
{
    public static function getChallenge(int $length = 20): string
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$'), 0, $length);
    }

    public static function toString(mixed $value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format(\DateTimeInterface::W3C);
        }
        if (is_float($value)) {
            return sprintf('%.2f', $value);
        }
        return (string) $value;
    }
}
