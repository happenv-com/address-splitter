<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\PolandParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Polish address parser - Strategy for streets that start with date/number-based names.
 *
 * Matches addresses like:
 * - "3 Maja 49"
 * - "11 Listopada 49 m. 3"
 * - "1000-lecia 15"
 * - "ul. 3 Maja 49"
 */
final class DateStreetStrategy extends AbstractParsingStrategy
{
    /**
     * Polish street prefixes.
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
     * Well-known Polish street names that start with numbers.
     */
    private const string NAMED_STREETS_WITH_NUMBERS = '(?:
        [12]?\d\s+(?:[Ss]tycznia|[Ll]utego|[Mm]arca|[Kk]wietnia|[Mm]aja|[Cc]zerwca|[Ll]ipca|[Ss]ierpnia|[Ww]rześnia|[Pp]aździernika|[Ll]istopada|[Gg]rudnia)
        | 1000[- ]?lecia
        | \d+[- ]?(?:Lecia|lecia)
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

        if (preg_match('/^((?:'.self::PREFIX_PATTERN.'\s+)?'.self::NAMED_STREETS_WITH_NUMBERS.'(?:\s+\p{L}[\p{L}.]*)*)\s+(\d+\S*?)(?:'.self::APARTMENT_SEPARATOR.'(\S+))?\s*$/xu', $address, $m) !== 0) {
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
