<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Dto;

final readonly class ParsedAddress
{
    public function __construct(
        public string $street,
        public ?string $buildingNumber = null,
        public ?string $apartmentNumber = null,
    ) {}
}
