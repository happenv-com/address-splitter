<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\PortugalParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Portuguese address parser - Street type strategy.
 *
 * Pattern: <street type> <name>[,] <number>[, <apartment>]
 *
 * Examples:
 * - "Rua Augusta 15"
 * - "Rua Augusta 15, 3º Esq."
 * - "Av. da Liberdade 120"
 * - "Praça do Comércio 1, R/C"
 */
final class StreetTypeStrategy extends AbstractParsingStrategy
{
    /**
     * Portuguese street type prefixes.
     */
    private const string STREET_TYPES = '(?:
        [Rr]ua | [Rr]\.
        | [Aa]venida | [Aa]v\.?
        | [Pp]raça | [Pp]ça\.?
        | [Tt]ravessa | [Tt]rav\.?
        | [Ll]argo
        | [Aa]lameda
        | [Bb]eco
        | [Cc]alcada | [Cc]alçada
        | [Ee]scadinhas
        | [Ee]strada | [Ee]st\.?
        | [Cc]aminho
    )';

    /**
     * Apartment/floor separators.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*,\s*
        | \s+[Ff]ração\.?\s*
        | \s+[Aa]ndar\.?\s*
        | \s+[Bb]loco\.?\s*
        | \s+[Ll]ote\.?\s*
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^('.self::STREET_TYPES.'\s+.+?)\s*[,]?\s+(\d+[a-zA-Z]?)(?:'.self::APARTMENT_SEPARATOR.'(.+))?\s*$/xu', $address, $m) !== 0) {
            return $this->buildResult($m[1], $m[2], $m[3] ?? null);
        }

        $this->fail($address);
    }
}
