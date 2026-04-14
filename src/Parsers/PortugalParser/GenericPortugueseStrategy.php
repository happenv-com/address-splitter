<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\PortugalParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Portuguese address parser - Generic fallback strategy.
 */
final class GenericPortugueseStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/floor separators.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*
        | \s+[Ff]ração\.?\s*
        | \s+[Aa]ndar\.?\s*
        | \s+[Bb]loco\.?\s*
        | \s+[Ll]ote\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s*[,]?\s+(\d+[a-zA-Z]?)(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }
}
