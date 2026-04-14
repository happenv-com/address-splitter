<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\GenericParsers;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Generic parser - Comma apartment strategy.
 *
 * Pattern: <street> <number>, <apartment info>
 */
final class CommaApartmentStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*,\s*(.+)\s*$/u', $address, $m) !== 0) {
            return new ParsedAddress(
                street: mb_trim($m[1]),
                buildingNumber: mb_trim($m[2]),
                apartmentNumber: mb_trim($m[3]),
            );
        }

        $this->fail($address);
    }
}
