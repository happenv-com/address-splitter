<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\PolandParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Polish address parser - Strategy for streets with Roman numerals in name.
 *
 * Matches addresses like:
 * - "Karola III Wielkiego 12"
 * - "Jana Pawła II 5"
 */
final class RomanNumeralStreetStrategy extends AbstractParsingStrategy
{
    /**
     * Roman numerals pattern.
     */
    private const string ROMAN_NUMERAL = '(?:X{0,3}(?:IX|IV|V?I{0,3}))';

    /**
     * Apartment/unit separators used in Poland.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*\/\s*
        | \s+[Mm](?:ieszkania|ieszk|\.?)?\s*\.?\s*
        | \s+[Ll]ok(?:al|\.?)?\s*\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+\s+'.self::ROMAN_NUMERAL.'(?:\s+\p{L}[\p{L}.]*)+)\s+(\d+\S*?)(?:'.self::APARTMENT_SEPARATOR.'(\S+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResultWithSlash($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }

    private function buildResultWithSlash(string $street, string $buildingNumber, ?string $apartmentNumber): ParsedAddress
    {
        $street = mb_trim($street);
        $buildingNumber = mb_trim($buildingNumber);

        if ($apartmentNumber === null && preg_match('/^(\d+[a-zA-Z]?)\s*\/\s*(\d+[a-zA-Z]?)$/', $buildingNumber, $parts)) {
            $buildingNumber = $parts[1];
            $apartmentNumber = $parts[2];
        }

        return $this->buildResult($street, $buildingNumber, $apartmentNumber);
    }
}
