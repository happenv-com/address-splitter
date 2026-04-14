<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter\ExternalParsers;

use Happenv\AddressSplitter\Contracts\AddressParsingStrategyInterface;
use Happenv\AddressSplitter\Contracts\HttpClientInterface;
use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Exceptions\CannotParseAddressException;

/**
 * Strategy that uses Google Address Validation API to parse addresses.
 *
 * @see https://developers.google.com/maps/documentation/address-validation
 */
final class GoogleAddressValidationStrategy implements AddressParsingStrategyInterface
{
    private const string API_URL = 'https://addressvalidation.googleapis.com/v1:validateAddress';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly ?string $regionCode = null,
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
        $requestBody = [
            'address' => [
                'addressLines' => [$address],
            ],
        ];

        if ($this->regionCode !== null) {
            $requestBody['address']['regionCode'] = $this->regionCode;
        }

        return $this->httpClient->post(
            self::API_URL.'?key='.$this->apiKey,
            [
                'json' => $requestBody,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $response
     */
    private function parseResponse(array $response, string $originalAddress): ParsedAddress
    {
        if (! isset($response['result']['address']['addressComponents'])) {
            throw new CannotParseAddressException($originalAddress);
        }

        $components = $response['result']['address']['addressComponents'];

        $street = $this->extractComponent($components, 'route');
        $streetNumber = $this->extractComponent($components, 'street_number');
        $subpremise = $this->extractComponent($components, 'subpremise');

        // If we couldn't extract a street, the parsing failed
        if ($street === null && $streetNumber === null) {
            throw new CannotParseAddressException($originalAddress);
        }

        return new ParsedAddress(
            street: $street ?? '',
            buildingNumber: $streetNumber,
            apartmentNumber: $subpremise,
        );
    }

    /**
     * @param  array<int, array{componentType: string, componentName: array{text: string}}>  $components
     */
    private function extractComponent(array $components, string $type): ?string
    {
        foreach ($components as $component) {
            if (($component['componentType'] ?? '') === $type) {
                return $component['componentName']['text'] ?? null;
            }
        }

        return null;
    }
}
