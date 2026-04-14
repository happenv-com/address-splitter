<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\SpainParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Spanish address parser - Generic fallback strategy.
 *
 * Pattern: street <number>[, apartment]
 */
final class GenericSpanishStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/floor/door separators.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*
        | \s+[Pp]iso\.?\s*
        | \s+[Pp](?:uerta|ta)\.?\s*
        | \s+[Ee]sc(?:alera)?\.?\s*
        | \s+[Pp]lanta\.?\s*
        | \s+[Dd]cha\.?\s*
        | \s+[Ii]zq(?:da)?\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s*[,]?\s+(\d+[a-zA-Z]?|[Ss]\/[Nn])(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }
}
