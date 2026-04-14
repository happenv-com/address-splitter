<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\CzechSlovakParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Czech/Slovak address parser - Simple number strategy.
 *
 * Pattern: <name> <number>[suffix]
 *
 * Examples:
 * - "Václavské náměstí 12"
 * - "Hlavná 12"
 * - "Obchodná 5a"
 */
final class SimpleNumberStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*$/u', $address, $m) !== 0) {
            return new ParsedAddress(street: mb_trim($m[1]), buildingNumber: $m[2]);
        }

        $this->fail($address);
    }
}
