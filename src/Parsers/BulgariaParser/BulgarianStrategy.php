<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\BulgariaParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Bulgarian address parser.
 *
 * Pattern: <street> <number>[, <extra info>]
 *
 * Examples:
 * - "ул. Витоша 15"
 * - "бул. Цар Освободител 12"
 * - "ул. Витоша 15, ап. 3"
 * - "ul. Vitosha 15"
 */
final class BulgarianStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-ZА-Яа-я]?)\s*(?:,\s*(.+))?\s*$/u', $address, $m) !== 0) {
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
