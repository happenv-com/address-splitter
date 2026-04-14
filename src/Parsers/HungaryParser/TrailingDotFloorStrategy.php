<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers\HungaryParser;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Parsers\AbstractParsingStrategy;

/**
 * Hungarian address parser - Trailing dot with floor info strategy.
 *
 * Pattern: <street> <number>. <floor info>
 *
 * Examples:
 * - "Váci u. 12. III/5"
 * - "Váci u. 12. 3. em. 5. ajtó"
 * - "Váci u. 12. fszt. 2"
 */
final class TrailingDotFloorStrategy extends AbstractParsingStrategy
{
    public function parse(string $address): ParsedAddress
    {
        $address = $this->normalizeWhitespace($address);

        if (preg_match('/^(.+?)\s+(\d+[a-zA-Z]?(?:\s*[-\/]\s*\d+[a-zA-Z]?)?)\.\s+(.+)$/u', $address, $m) !== 0) {
            return new ParsedAddress(
                street: mb_trim($m[1]),
                buildingNumber: mb_trim($m[2]),
                apartmentNumber: mb_trim($m[3]),
            );
        }

        $this->fail($address);
    }
}
