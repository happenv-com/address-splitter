<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\GenericParsers;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Generic parser - Number first strategy.
 *
 * Pattern: <number>[suffix] <street>
 */
final class NumberFirstStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(\d+[a-zA-Z]?)\s+(.+)$/u', $address, $m) !== 0) {
            return new ParsedAddress(
                street: mb_trim($m[2]),
                buildingNumber: mb_trim($m[1]),
            );
        }

        $this->fail($address);
    }
}
