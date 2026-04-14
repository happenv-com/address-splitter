<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\BritishParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * British/Irish address parser - Flat prefix strategy.
 *
 * Pattern: "Flat X, <number> <street>"
 *
 * Examples:
 * - "Flat 3, 15 High Street"
 * - "Unit 4, 20 King's Road"
 */
final class FlatPrefixStrategy extends AbstractParsingStrategy
{
    /**
     * Flat/unit prefixes.
     */
    private const string FLAT_PREFIX = '(?:[Ff]lat|[Aa]pt\.?|[Uu]nit|[Ss]uite|[Rr]oom)';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^'.self::FLAT_PREFIX.'\s+(\S+)\s*[,]\s*(\d+[a-zA-Z]?(?:\s*-\s*\d+[a-zA-Z]?)?)\s+(.+)$/iu', $address, $m) !== 0) {
            return $this->buildResult($m[3], $m[2], $m[1]);
        }

        $this->fail($address);
    }
}
