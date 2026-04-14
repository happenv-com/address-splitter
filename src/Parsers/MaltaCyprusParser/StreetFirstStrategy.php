<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\MaltaCyprusParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Malta/Cyprus address parser - Street first strategy.
 *
 * Pattern: "<street> <number>"
 *
 * Examples:
 * - "Triq ir-Repubblika 15"
 * - "Makariou 120"
 */
final class StreetFirstStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*(?:,\s*(.+))?\s*$/u', $address, $m) !== 0) {
            $apartment = isset($m[3]) ? mb_trim($m[3]) : null;

            return new ParsedAddress(
                street: mb_trim($m[1]),
                buildingNumber: mb_trim($m[2]),
                apartmentNumber: $apartment,
            );
        }

        $this->fail($address);
    }
}
