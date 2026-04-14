<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\CzechSlovakParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Czech/Slovak address parser - Prefix with dual numbers strategy.
 *
 * Pattern: <prefix> <name> <number>/<number>
 *
 * Examples:
 * - "nám. Míru 820/9"
 * - "tř. Kpt. Jaroše 1922/3"
 */
final class PrefixDualNumberStrategy extends AbstractParsingStrategy
{
    /**
     * Czech/Slovak street prefixes.
     */
    private const string PREFIX_PATTERN = '(?:
        [Uu]l(?:ice|\.)?
        | [Nn]ám(?:ěstí|\.)?
        | [Tt]ř(?:ída|\.)?
        | [Ss]ady
        | [Nn]ábř(?:eží|\.)?
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^('.self::PREFIX_PATTERN.'\s+.+?)\s+(\d+[a-zA-Z]?)\s*\/\s*(\d+[a-zA-Z]?)\s*$/xu', $address, $m) !== 0) {
            return new ParsedAddress(street: mb_trim($m[1]), buildingNumber: $m[2], apartmentNumber: $m[3]);
        }

        $this->fail($address);
    }
}
