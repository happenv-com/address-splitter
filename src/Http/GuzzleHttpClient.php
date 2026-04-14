<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Http;

use GuzzleHttp\ClientInterface;
use Happenv\AddressSplitter\Contracts\HttpClientInterface;

final class GuzzleHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly ClientInterface $client,
    ) {}

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function post(string $url, array $options = []): array
    {
        $response = $this->client->request('POST', $url, $options);

        $body = $response->getBody()->getContents();

        /** @var array<string, mixed> */
        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }
}
