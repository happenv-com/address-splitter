<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\GermanicParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * German/Austrian/Swiss address parser.
 *
 * Pattern: <street> <building_number>[suffix] [separator <apartment>]
 *
 * Matches:
 * - "Hauptstraße 12"
 * - "Müllerstr. 5a"
 * - "Bahnhofstr. 12/3"
 * - "Schönbrunner Str. 23 Top 4"
 */
final class GermanicStrategy extends AbstractParsingStrategy
{
    /**
     * Apartment/unit separators used in German-speaking countries.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*\/\s*
        | \s*,\s*
        | \s+(?:[Ww](?:oh)?n(?:ung)?|[Ww]hg)\.?\s*
        | \s+[Tt]op\.?\s*
        | \s+[Ss]tiege\.?\s*
        | \s+[Nn]r\.?\s*
        | \s+[Aa]pp(?:artement|\.?)?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+(?:\s*[a-zA-Z]|\s*-\s*\d+[a-zA-Z]?)?)(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildGermanicResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }

    private function buildGermanicResult(string $street, string $buildingNumber, ?string $apartmentNumber): ParsedAddress
    {
        $street = mb_trim($street);
        $buildingNumber = mb_trim(str_replace(' ', '', $buildingNumber));

        if ($apartmentNumber === null && preg_match('/^(\d+[a-zA-Z]?)\s*\/\s*(\d+[a-zA-Z]?)$/', $buildingNumber, $parts)) {
            $buildingNumber = $parts[1];
            $apartmentNumber = $parts[2];
        }

        return $this->buildResult($street, $buildingNumber, $apartmentNumber);
    }
}
