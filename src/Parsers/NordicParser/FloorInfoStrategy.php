<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\NordicParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Nordic address parser - Floor info strategy.
 *
 * Pattern: <street> <number><letter>[,] <apartment/floor info>
 *
 * Examples:
 * - "Storgatan 15, 2 tr"
 * - "Vestergade 10, 3. sal th"
 * - "Vestergade 10 st. tv."
 */
final class FloorInfoStrategy extends AbstractParsingStrategy
{
    /**
     * Nordic floor/staircase indicators that follow a building number + letter.
     */
    private const string NORDIC_FLOOR = '(?:
        \d+\s*\.?\s*(?:[Ss]al|[Tt]r(?:appa)?\.?|[Tt]h\.?|[Tt]v\.?|[Ss]t\.?|[Mm]f\.?)
        | [Ss]t\.?\s*(?:[Tt]h\.?|[Tt]v\.?)?
    )';

    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+)\s*([a-zA-Z])?\s*[,]?\s+('.self::NORDIC_FLOOR.'(?:\s+.*)?)$/xu', $address, $m) !== 0) {
            $building = $m[2].$m[3];

            return $this->buildResult($m[1], $building, $m[4]);
        }

        $this->fail($address);
    }
}
