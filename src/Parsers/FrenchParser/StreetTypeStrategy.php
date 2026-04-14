<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\FrenchParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * French/Belgian/Luxembourgish address parser - Street type strategy.
 *
 * Pattern: <number>[suffix] [,] <street type> <street name> [separator <apartment>]
 *
 * Matches:
 * - "15 Rue de la Paix"
 * - "15bis Rue de la Paix"
 * - "23 Avenue des Champs-Élysées"
 * - "3 Place de la Concorde, App. 5"
 */
final class StreetTypeStrategy extends AbstractParsingStrategy
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

        if (preg_match('/^(\d+\s*'.self::BUILDING_SUFFIX.')\s*[,]?\s*('.self::STREET_TYPES.'\s+.+?)(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            $buildingNumber = $this->normalizeBuildingNumber($m[1]);

            return $this->buildResult($m[2], $buildingNumber, $m[3] ?? null);
        }

        $this->fail($address);
    }

    /**
     * Normalize building number by removing space between number and suffix (e.g., "15 bis" → "15bis").
     */
    private function normalizeBuildingNumber(string $buildingNumber): string
    {
        return (string) preg_replace('/^(\d+)\s+(bis|ter|quater)$/i', '$1$2', mb_trim($buildingNumber));
    }
}
