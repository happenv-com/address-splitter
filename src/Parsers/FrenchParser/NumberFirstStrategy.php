<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\FrenchParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * French/Belgian/Luxembourgish address parser - Generic number-first strategy.
 *
 * Pattern: <number>[suffix] <name> [separator <apartment>]
 *
 * Matches:
 * - "15 Église"
 * - "23bis Marché"
 */
final class NumberFirstStrategy extends AbstractParsingStrategy
{
    /**
     * French building number suffixes.
     */
    private const string BUILDING_SUFFIX = '(?:[Bb]is|[Tt]er|[Qq]uater)?';

    /**
     * Apartment/unit separators used in French-speaking countries.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*[Aa]pp(?:artement|t)?\.?\s*
        | \s*,\s*[Éé]t(?:age)?\.?\s*
        | \s*,\s*[Bb]te\.?\s*
        | \s*,\s*[Bb]oîte\.?\s*
        | \s*,\s*[Pp]orte\.?\s*
        | \s*,\s*
        | \s+[Aa]pp(?:artement|t)?\.?\s*
        | \s+[Éé]t(?:age)?\.?\s*
        | \s+[Bb]te\.?\s*
        | \s+[Bb]oîte\.?\s*
        | \s+[Pp]orte\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(\d+\s*'.self::BUILDING_SUFFIX.')\s*[,]?\s+(.+?)(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[2], mb_trim($m[1]), $m[3] ?? null);
        }

        $this->fail($address);
    }
}
