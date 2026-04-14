<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\NetherlandsParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Dutch address parser - Standard strategy.
 *
 * Pattern: Street <number>[-<apartment>] or Street <number> <letter>
 *
 * Examples:
 * - "Keizersgracht 123"
 * - "Keizersgracht 123-II"
 * - "Keizersgracht 123 A"
 * - "Prinsengracht 263-hs"
 */
final class StandardDutchStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+)\s*([a-zA-Z])?(?:\s*-\s*(.+))?\s*$/u', $address, $m) !== 0) {
            $building = $m[2].($m[3] ?? '');

            return $this->buildResult($m[1], $building, $m[4] ?? null);
        }

        $this->fail($address);
    }
}
