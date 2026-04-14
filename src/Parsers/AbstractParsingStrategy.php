<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Parsers;

use Happenv\AddressSplitter\Contracts\AddressParsingStrategyInterface;
use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Exceptions\CannotParseAddressException;

abstract class AbstractParsingStrategy implements AddressParsingStrategyInterface
{
    protected function normalizeWhitespace(string $input): string
    {
        return (string) preg_replace('/\s+/u', ' ', mb_trim($input));
    }

    /**
     * @throws CannotParseAddressException
     */
    protected function fail(string $address): never
    {
        throw new CannotParseAddressException($address);
    }

    protected function buildResult(string $street, string $buildingNumber, ?string $apartmentNumber): ParsedAddress
    {
        $street = mb_trim($street);
        $street = mb_rtrim($street, ',');
        $street = mb_trim($street);

        $buildingNumber = mb_trim($buildingNumber);

        $apartmentNumber = $apartmentNumber !== null ? mb_trim($apartmentNumber) : null;

        if ($apartmentNumber === '') {
            $apartmentNumber = null;
        }

        return new ParsedAddress(
            street: $street,
            buildingNumber: $buildingNumber,
            apartmentNumber: $apartmentNumber,
        );
    }
}
