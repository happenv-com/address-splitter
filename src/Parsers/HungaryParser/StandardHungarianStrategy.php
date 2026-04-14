<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\HungaryParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Hungarian address parser - Standard strategy.
 *
 * Pattern: <street> <number>[, apartment]
 *
 * Examples:
 * - "Váci utca 12"
 * - "Andrássy út 60"
 * - "Kossuth tér 1-3"
 */
final class StandardHungarianStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?(?:\s*[-\/]\s*\d+[a-zA-Z]?)?)\s*(?:,\s*(.+))?\s*$/u', $address, $m) !== 0) {
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
