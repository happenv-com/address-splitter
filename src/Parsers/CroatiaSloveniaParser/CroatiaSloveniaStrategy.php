<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\CroatiaSloveniaParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Croatian and Slovenian address parser.
 *
 * Pattern: <street> <number>[/<apartment>]
 *
 * Examples:
 * - "Ilica 15"
 * - "Ilica 15/3"
 * - "Trg bana Jelačića 1"
 * - "Prešernova cesta 12"
 * - "Slovenska cesta 55a"
 */
final class CroatiaSloveniaStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?)\s*(?:\/\s*(\d+[a-zA-Z]?))?\s*$/u', $address, $m) !== 0) {
            $apartment = $m[3] ?? null;

            return new ParsedAddress(
                street: mb_trim($m[1]),
                buildingNumber: mb_trim($m[2]),
                apartmentNumber: $apartment,
            );
        }

        $this->fail($address);
    }
}
