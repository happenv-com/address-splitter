<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\FrenchParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * French address parser - Street name followed by number (rare but possible).
 *
 * Pattern: <street type> <street name> <number>[suffix]
 *
 * Matches:
 * - "Rue de la Paix 15bis"
 */
final class StreetFirstStrategy extends AbstractParsingStrategy
{
    /**
     * French street type prefixes.
     */
    private const string STREET_TYPES = '(?:
        [Rr]ue
        | [Aa]venue | [Aa]v\.?
        | [Bb]oulevard | [Bb]d\.? | [Bb]lvd\.?
        | [Pp]lace | [Pp]l\.?
        | [Cc]hemin
        | [Aa]ll[ée]e
        | [Ii]mpasse
        | [Pp]assage
        | [Cc]ours
        | [Qq]uai
        | [Ff]aubourg | [Ff]bg\.?
        | [Ss]entier
        | [Rr]oute | [Rr]te\.?
        | [Vv]oie
        | [Ss]quare
        | [Rr]ond[- ]?[Pp]oint
        | [Cc]arrefour
        | [Mm]ontée
        | [Tt]raverse
        | [Cc]ité
    )';

    /**
     * French building number suffixes.
     */
    private const string BUILDING_SUFFIX = '(?:[Bb]is|[Tt]er|[Qq]uater)?';

    /**
     * Apartment/unit separators used in French-speaking countries.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*[Aa]pp(?:artement|t)?\.?\s*
        | \s*,\s*[Éé]t(?:age)?\.?\s*
        | \s*,\s*[Bb]te\.?\s*
        | \s*,\s*[Bb]oîte\.?\s*
        | \s*,\s*[Pp]orte\.?\s*
        | \s*,\s*
        | \s+[Aa]pp(?:artement|t)?\.?\s*
        | \s+[Éé]t(?:age)?\.?\s*
        | \s+[Bb]te\.?\s*
        | \s+[Bb]oîte\.?\s*
        | \s+[Pp]orte\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^('.self::STREET_TYPES.'\s+.+?)\s+(\d+\s*'.self::BUILDING_SUFFIX.')(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[1], mb_trim($m[2]), $m[3] ?? null);
        }

        $this->fail($address);
    }
}
