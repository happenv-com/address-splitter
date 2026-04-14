<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\CzechSlovakParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Czech/Slovak address parser - Dual number strategy.
 *
 * Pattern: <name> <number>/<number>
 *
 * Examples:
 * - "Karlova 1/15"
 */
final class DualNumberStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*\/\s*(\d+[a-zA-Z]?)\s*$/u', $address, $m) !== 0) {
            return new ParsedAddress(street: mb_trim($m[1]), buildingNumber: $m[2], apartmentNumber: $m[3]);
        }

        $this->fail($address);
    }
}
