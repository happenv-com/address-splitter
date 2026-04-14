<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\NetherlandsParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Dutch address parser - Bus/Verdieping strategy.
 *
 * Pattern: Street <number> bus/verd <apartment>
 *
 * Examples:
 * - "Lange Voorhout 8 bus 2"
 * - "Herengracht 100 verd. 3"
 */
final class BusSeparatorStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/unit separators.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*-\s*
        | \s+[Bb]us\.?\s*
        | \s+[Vv]erd(?:ieping)?\.?\s*
        | \s+[Hh]oog\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }
}
