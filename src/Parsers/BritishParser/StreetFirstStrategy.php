<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\BritishParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * British/Irish address parser - Street first strategy (rare but possible).
 *
 * Pattern: "<street> <number>"
 */
final class StreetFirstStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*$/u', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2], null);
        }

        $this->fail($address);
    }
}
