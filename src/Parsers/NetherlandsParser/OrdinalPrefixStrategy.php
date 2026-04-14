<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\NetherlandsParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Dutch address parser - Ordinal prefix strategy.
 *
 * Matches streets starting with ordinal prefixes like "2e", "1ste".
 *
 * Examples:
 * - "2e Constantijn Huygensstraat 10"
 * - "1ste Helmersstraat 5-II"
 */
final class OrdinalPrefixStrategy extends AbstractParsingStrategy
{
    /**
     * Known Dutch ordinal prefixes that start street names.
     */
    private const string ORDINAL_PREFIX = '(?:\d+(?:e|ste|de))\s+';

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

        if (preg_match('/^('.self::ORDINAL_PREFIX.'.+?)\s+(\d+)\s*([a-zA-Z])?(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            $building = $m[2].($m[3] ?? '');

            return $this->buildResult($m[1], $building, $m[4] ?? null);
        }

        $this->fail($address);
    }
}
