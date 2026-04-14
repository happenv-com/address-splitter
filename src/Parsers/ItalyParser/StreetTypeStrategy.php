<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\ItalyParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Italian address parser - Street type strategy.
 *
 * Pattern: <street type> <name>[,] <number>[/<apartment>]
 *
 * Examples:
 * - "Via Roma 15"
 * - "Via Roma, 15"
 * - "Via Roma 15/A"
 * - "Piazza Duomo 1"
 * - "Corso Vittorio Emanuele 23, int. 5"
 */
final class StreetTypeStrategy extends AbstractParsingStrategy
{
    /**
     * Italian street type prefixes.
     */
    private const string STREET_TYPES = '(?:
        [Vv]ia | [Vv]iale | [Vv]\.le
        | [Pp]iazza | [Pp]\.za | [Pp]\.zza
        | [Cc]orso | [Cc]\.so
        | [Ll]argo
        | [Vv]icolo
        | [Cc]ontrada
        | [Bb]orgo
        | [Ll]ungom(?:are|\.)?
        | [Ss]trada
        | [Ss]alita
        | [Cc]alle
        | [Ff]ondamenta
        | [Rr]iva
        | [Rr]ampa
        | [Tt]raversa
        | [Rr]ondò
        | [Ss]tretto[ij]a
        | [Pp]assaggio
        | [Cc]amp(?:iello|o)?
    )';

    /**
     * Apartment/unit separators used in Italy.
     */
    private const string APARTMENT_SEPARATOR = '(?:
        \s*\/\s*
        | \s*,\s*[Ii]nt(?:erno)?\.?\s*
        | \s*,\s*[Ss]c(?:ala)?\.?\s*
        | \s*,\s*[Pp](?:iano)?\.?\s*
        | \s*,\s*
        | \s+[Ii]nt(?:erno)?\.?\s*
        | \s+[Ss]c(?:ala)?\.?\s*
        | \s+[Pp](?:iano)?\.?\s*
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
