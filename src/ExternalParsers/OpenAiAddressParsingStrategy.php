<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\ExternalParsers;

use Happenv\AddressSplitter\Contracts\AddressParsingStrategyInterface;
use Happenv\AddressSplitter\Contracts\HttpClientInterface;
use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Exceptions\CannotParseAddressException;

/**
 * Strategy that uses OpenAI API to parse addresses using structured outputs.
 *
 * @see https://platform.openai.com/docs/guides/structured-outputs
 */
final class OpenAiAddressParsingStrategy implements AddressParsingStrategyInterface
{
    private const string API_URL = 'https://api.openai.com/v1/chat/completions';

    private const string DEFAULT_MODEL = 'gpt-4o-mini';

    private const string SYSTEM_PROMPT = <<<'PROMPT'
You are an address parser. Your task is to extract address components from the given address string.
Extract the following components:
- street: The street name (without building number)
- buildingNumber: The building/house number (may include letter suffix like "12a")
- apartmentNumber: The apartment/unit/flat number if present

Return ONLY a valid JSON object with these exact keys. If a component is not present, use null.
Do not include any explanation or additional text.
PROMPT;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $model = self::DEFAULT_MODEL,
        private readonly ?string $countryHint = null,
    ) {}

    public function parse(string $address): ParsedAddress
    {
        $address = mb_trim($address);

        if ($address === '') {
            throw new CannotParseAddressException($address);
        }

        $response = $this->callApi($address);

        return $this->parseResponse($response, $address);
    }

    /**
     * @return array<string, mixed>
     */
    private function callApi(string $address): array
    {
        $userPrompt = $this->buildUserPrompt($address);

        $requestBody = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => self::SYSTEM_PROMPT,
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'parsed_address',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'street' => [
                                'type' => ['string', 'null'],
                                'description' => 'The street name without building number',
                            ],
                            'buildingNumber' => [
                                'type' => ['string', 'null'],
                                'description' => 'The building or house number, may include letter suffix',
                            ],
                            'apartmentNumber' => [
                                'type' => ['string', 'null'],
                                'description' => 'The apartment, unit, or flat number',
                            ],
                        ],
                        'required' => ['street', 'buildingNumber', 'apartmentNumber'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'temperature' => 0,
        ];

        return $this->httpClient->post(
            self::API_URL,
            [
                'json' => $requestBody,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->apiKey,
                ],
            ]
        );
    }

    private function buildUserPrompt(string $address): string
    {
        $prompt = "Parse this address: \"{$address}\"";

        if ($this->countryHint !== null) {
            $prompt .= "\nCountry hint: {$this->countryHint}";
        }

        return $prompt;
    }

    /**
     * @param  array<string, mixed>  $response
     */
    private function parseResponse(array $response, string $originalAddress): ParsedAddress
    {
        $content = $response['choices'][0]['message']['content'] ?? null;

        if ($content === null) {
            throw new CannotParseAddressException($originalAddress);
        }

        /** @var array{street: string|null, buildingNumber: string|null, apartmentNumber: string|null} $parsed */
        $parsed = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $street = $parsed['street'] ?? null;
        $buildingNumber = $parsed['buildingNumber'] ?? null;
        $apartmentNumber = $parsed['apartmentNumber'] ?? null;

        // If we couldn't extract meaningful data, fail
        if ($street === null && $buildingNumber === null) {
            throw new CannotParseAddressException($originalAddress);
        }

        return new ParsedAddress(
            street: $street ?? '',
            buildingNumber: $buildingNumber,
            apartmentNumber: $apartmentNumber,
        );
    }
}
