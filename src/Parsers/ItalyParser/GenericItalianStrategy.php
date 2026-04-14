<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\ItalyParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Italian address parser - Generic fallback strategy.
 *
 * Pattern: street <number> [separator apartment]
 */
final class GenericItalianStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/unit separators used in Italy.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*\/\s*
        | \s*,\s*[Ii]nt(?:erno)?\.?\s*
        | \s*,\s*[Ss]c(?:ala)?\.?\s*
        | \s*,\s*[Pp](?:iano)?\.?\s*
        | \s*,\s*
        | \s+[Ii]nt(?:erno)?\.?\s*
        | \s+[Ss]c(?:ala)?\.?\s*
        | \s+[Pp](?:iano)?\.?\s*
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
