<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Contracts;

use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Exceptions\CannotParseAddressException;

interface AddressParsingStrategyInterface
{
    /**
     * Try to parse an address string.
     *
     * @throws CannotParseAddressException if this strategy cannot parse the address format
     */
    public function parse(string $address): ParsedAddress;
}
