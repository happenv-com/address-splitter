<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Exceptions;

use Exception;

final class CannotParseAddressException extends Exception
{
    public function __construct(string $address)
    {
        parent::__construct("Cannot parse address: {$address}");
    }
}
