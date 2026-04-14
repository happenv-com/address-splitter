<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Contracts;

interface HttpClientInterface
{
    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function post(string $url, array $options = []): array;
}
