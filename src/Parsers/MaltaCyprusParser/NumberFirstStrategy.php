<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\MaltaCyprusParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Malta/Cyprus address parser - Number first strategy (English style).
 *
 * Pattern: "<number> <street>"
 *
 * Examples:
 * - "15 Republic Street"
 * - "15 Kennedy Avenue"
 */
final class NumberFirstStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(\d+[a-zA-Z]?)\s+(.+?)(?:\s*,\s*(.+))?\s*$/u', $address, $m) !== 0) {
            $apartment = isset($m[3]) ? mb_trim($m[3]) : null;

            return new ParsedAddress(
                street: mb_trim($m[2]),
                buildingNumber: mb_trim($m[1]),
                apartmentNumber: $apartment,
            );
        }

        $this->fail($address);
    }
}
