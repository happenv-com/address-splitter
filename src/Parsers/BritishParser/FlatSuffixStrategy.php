<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\BritishParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * British/Irish address parser - Flat suffix strategy.
 *
 * Pattern: "<number> <street>, Flat X"
 *
 * Examples:
 * - "15 High Street, Flat 3"
 */
final class FlatSuffixStrategy extends AbstractParsingStrategy
{
    /**
     * Flat/unit prefixes.
     */
    private const string FLAT_PREFIX = '(?:[Ff]lat|[Aa]pt\.?|[Uu]nit|[Ss]uite|[Rr]oom)';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(\d+[a-zA-Z]?(?:\s*-\s*\d+[a-zA-Z]?)?)\s+(.+?)\s*,\s*'.self::FLAT_PREFIX.'\s+(\S+)\s*$/iu', $address, $m) !== 0) {
            return $this->buildResult($m[2], $m[1], $m[3]);
        }

        $this->fail($address);
    }
}
