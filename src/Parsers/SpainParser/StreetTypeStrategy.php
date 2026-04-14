<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\SpainParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Spanish address parser - Street type strategy.
 *
 * Pattern: <street type> <name>[,] <number/s/n>[, <apartment info>]
 *
 * Examples:
 * - "Calle Mayor 15"
 * - "C/ Mayor 15"
 * - "Calle Mayor 15, 3º 2ª"
 * - "Avda. de la Constitución 12"
 * - "Plaza de España s/n"
 */
final class StreetTypeStrategy extends AbstractParsingStrategy
{
    /**
     * Spanish street type prefixes.
     */
    private const string STREET_TYPES = '(?:
        [Cc]alle | C\/ | [Cc]l\.?
        | [Aa]venida | [Aa]vda\.? | [Aa]v\.?
        | [Pp]laza | [Pp]l\.? | [Pp]za\.?
        | [Pp]aseo | [Pp]º
        | [Cc]amino | [Cc]mno\.?
        | [Cc]arretera | [Cc]tra\.?
        | [Rr]onda
        | [Tt]ravesía | [Tt]rav\.?
        | [Gg]lorieta
        | [Bb]ulevar | [Bb]lvr\.?
        | [Cc]ostanilla
        | [Cc]allejón
        | [Pp]articular
    )';

    /**
     * Apartment/floor/door separators.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*
        | \s+[Pp]iso\.?\s*
        | \s+[Pp](?:uerta|ta)\.?\s*
        | \s+[Ee]sc(?:alera)?\.?\s*
        | \s+[Pp]lanta\.?\s*
        | \s+[Dd]cha\.?\s*
        | \s+[Ii]zq(?:da)?\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^('.self::STREET_TYPES.'\s+.+?)\s*[,]?\s+(\d+[a-zA-Z]?|[Ss]\/[Nn])(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }
}
