<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\PolandParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Polish address parser - Generic fallback strategy.
 *
 * Matches generic "name <number>[/apartment]" pattern.
 * - "Słoneczna 15"
 * - "Długa 12a"
 * - "Krótka 5/3"
 */
final class GenericPolishStrategy extends AbstractParsingStrategy
{
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

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?(?:\s*[-\/]\s*\d+[a-zA-Z]?)?)(?:'.self::APARTMENT_SEPARATOR.'(\S+))?\s*$/xu', $address, $m) !== 0) {
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
