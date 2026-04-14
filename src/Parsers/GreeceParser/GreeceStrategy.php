<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\GreeceParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Greek address parser.
 *
 * Pattern: <street> <number>[, <apartment info>]
 *
 * Examples:
 * - "Οδός Ερμού 15"
 * - "Ερμού 15"
 * - "Λεωφόρος Αλεξάνδρας 120"
 * - "Ερμού 15, 3ος όροφος"
 */
final class GreeceStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Zα-ωΑ-Ω]?)\s*(?:,\s*(.+))?\s*$/u', $address, $m) !== 0) {
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
