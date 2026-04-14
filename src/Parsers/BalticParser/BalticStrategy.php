<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\BalticParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Baltic states address parser (Lithuania, Latvia, Estonia).
 *
 * Pattern: <street> <number>[separator <apartment>]
 *
 * Examples:
 * - "Gedimino pr. 15"
 * - "Gedimino pr. 15-3"
 * - "Brīvības iela 15, dz. 3"
 * - "Viru 15, krt 3"
 */
final class BalticStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/unit separators.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*-\s*
        | \s*,\s*(?:[Bb]t\.?\s*|[Dd]z\.?\s*|[Kk]rt\.?\s*)?
        | \s+[Bb]t\.?\s*
        | \s+[Dd]z\.?\s*
        | \s+[Kk]rt\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)(?:'.self::APARTMENT_SEPARATOR.'(\d+[a-zA-Z]?))?\s*$/xu', $address, $m) !== 0) {
            $apartment = isset($m[3]) ? mb_trim($m[3]) : null;

            return new ParsedAddress(
                street: mb_trim($m[1]),
                buildingNumber: mb_trim($m[2]),
                apartmentNumber: $apartment,
            );
        }

        $this->fail($address);
    }
}
