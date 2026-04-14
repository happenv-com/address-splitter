<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\RomaniaParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Romanian address parser - Nr. prefix strategy.
 *
 * Pattern: <street> nr. <number>[, <extra info with bl./sc./et./ap.>]
 *
 * Examples:
 * - "Str. Victoriei nr. 15"
 * - "Str. Victoriei nr. 15, ap. 3"
 * - "Str. Victoriei nr. 15, bl. A, sc. 2, et. 3, ap. 12"
 */
final class NrPrefixStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+[Nn]r\.?\s*(\d+[a-zA-Z]?)\s*(?:,\s*(.+))?\s*$/u', $address, $m) !== 0) {
            return $this->buildRomanianResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }

    private function buildRomanianResult(string $street, string $buildingNumber, ?string $extraInfo): ParsedAddress
    {
        $street = mb_trim($street);
        $buildingNumber = mb_trim($buildingNumber);

        $apartment = null;
        if ($extraInfo !== null && $extraInfo !== '') {
            $apartment = mb_trim($extraInfo);
        }

        if ($apartment === '') {
            $apartment = null;
        }

        return new ParsedAddress(
            street: $street,
            buildingNumber: $buildingNumber,
            apartmentNumber: $apartment,
        );
    }
}
