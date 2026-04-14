<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\NordicParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Nordic address parser - Finnish style strategy.
 *
 * Pattern: <street> <number> <letter> <apartment number> (Finnish style: "5 A 12")
 *
 * Examples:
 * - "Mannerheimintie 5 A 12"
 */
final class FinnishStyleStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+)\s+([a-zA-Z])\s+(\d+)\s*$/u', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2].$m[3], $m[4]);
        }

        $this->fail($address);
    }
}
