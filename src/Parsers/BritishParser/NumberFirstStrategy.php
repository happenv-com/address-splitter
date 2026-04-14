<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\BritishParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * British/Irish address parser - Number first strategy.
 *
 * Pattern: "<number>[suffix] <street>"
 *
 * Examples:
 * - "15 High Street"
 * - "15a High Street"
 * - "12-14 Oxford Street"
 */
final class NumberFirstStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(\d+[a-zA-Z]?(?:\s*-\s*\d+[a-zA-Z]?)?)\s+(.+)$/u', $address, $m) !== 0) {
            return $this->buildResult($m[2], $m[1], null);
        }

        $this->fail($address);
    }
}
