<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\NordicParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Nordic address parser - Standard strategy.
 *
 * Pattern: <street> <number><letter> [separator] <apartment number>
 *
 * Examples:
 * - "Storgatan 15"
 * - "Storgatan 15 B"
 * - "Storgatan 15 B lgh 1203"
 * - "Mannerheimintie 5, as. 12"
 */
final class StandardNordicStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/unit separators used in Nordic countries.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*
        | \s+[Ll]gh\.?\s*
        | \s+[Ll]ägenhet\.?\s*
        | \s+[Aa]s\.?\s*
        | \s+[Aa]sunto\.?\s*
        | \s+[Bb]st\.?\s*
        | \s+[Vv]ån(?:ing)?\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+)\s*([a-zA-Z])?(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            $building = $m[2].($m[3] ?? '');

            return $this->buildResult($m[1], $building, $m[4] ?? null);
        }

        $this->fail($address);
    }
}
