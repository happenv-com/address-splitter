<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\Http;

use Happenv\AddressSplitter\Contracts\HttpClientInterface;
use RuntimeException;

final class CurlHttpClient implements HttpClientInterface
{
    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function post(string $url, array $options = []): array
    {
        $ch = curl_init($url);

        if ($ch === false) {
            throw new RuntimeException('Failed to initialize cURL');
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        if (isset($options['json'])) {
            $jsonBody = json_encode($options['json'], JSON_THROW_ON_ERROR);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: '.strlen($jsonBody),
            ]);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('cURL error: '.$error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new RuntimeException('HTTP error: '.$httpCode);
        }

        /** @var array<string, mixed> */
        return json_decode((string) $response, true, 512, JSON_THROW_ON_ERROR);
    }
}
