<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\GenericParsers;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Generic parser - Street first strategy.
 *
 * Pattern: <street> <number>[suffix]
 */
final class StreetFirstStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*$/u', $address, $m) !== 0) {
            return new ParsedAddress(
                street: mb_trim($m[1]),
                buildingNumber: mb_trim($m[2]),
            );
        }

        $this->fail($address);
    }
}
