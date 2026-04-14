<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\RomaniaParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Romanian address parser - Standard strategy.
 *
 * Pattern: <street> <number>[, <extra info>]
 *
 * Examples:
 * - "Str. Victoriei 15"
 * - "Bd. Unirii 1, bl. B3, ap. 45"
 */
final class StandardRomanianStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*(?:,\s*(.+))?\s*$/u', $address, $m) !== 0) {
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
