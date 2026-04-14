<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\PolandParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Polish address parser - Strategy for addresses with explicit street prefix.
 *
 * Matches addresses like:
 * - "ul. 3 Maja 49"
 * - "al. Wojska Polskiego 49a"
 * - "os. Tysiąclecia 15/3"
 * - "Rondo Mogilskie 1"
 * - "pl. Wolności 5"
 */
final class PrefixedStreetStrategy extends AbstractParsingStrategy
{
    /**
     * Polish street prefixes that indicate the beginning of a street name.
     */
    private const string PREFIX_PATTERN = '(?:
        [Uu]l(?:ica|\.)?
        | [Aa]l(?:eja|\.)?
        | [Aa]lee?[ij]?a?
        | [Oo]s(?:iedle|\.)?
        | [Pp]l(?:ac|\.)?
        | [Rr]ondo
        | [Ss]kr(?:zyżowanie|\.)?
        | [Bb]ulw(?:ar|\.)?
        | [Ww]ybrzeże
        | [Pp]lażow[aey]
        | [Nn]abrzeże
        | [Ss]zosa
        | [Tt]rakt
        | [Dd]roga
        | [Gg]ościniec
    )';

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

        if (preg_match('/^('.self::PREFIX_PATTERN.'\s+.+?)\s+(\d+\S*?)(?:'.self::APARTMENT_SEPARATOR.'(\S+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResultWithSlash($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }

    private function buildResultWithSlash(string $street, string $buildingNumber, ?string $apartmentNumber): ParsedAddress
    {
        $street = mb_trim($street);
        $buildingNumber = mb_trim($buildingNumber);

        // Handle building/apartment combined with slash: "15/3"
        if ($apartmentNumber === null && preg_match('/^(\d+[a-zA-Z]?)\s*\/\s*(\d+[a-zA-Z]?)$/', $buildingNumber, $parts)) {
            $buildingNumber = $parts[1];
            $apartmentNumber = $parts[2];
        }

        return $this->buildResult($street, $buildingNumber, $apartmentNumber);
    }
}
