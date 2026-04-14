<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter;

use Happenv\AddressSplitter\Contracts\AddressParsingStrategyInterface;
use Happenv\AddressSplitter\Dto\ParsedAddress;
use Happenv\AddressSplitter\Exceptions\CannotParseAddressException;

final class AddressSplitterService
{
    /**
     * @var array<string, list<AddressParsingStrategyInterface>>
     */
    private array $strategies = [];

    /**
     * @var list<AddressParsingStrategyInterface>
     */
    private array $fallbackStrategies;

    /**
     * @var list<AddressParsingStrategyInterface>
     */
    private array $externalStrategies;

    private bool $useExternalStrategies;

    private readonly BestResultSelector $resultSelector;

    public function __construct(?AddressSplitterConfiguration $configuration = null)
    {
        $configuration ??= AddressSplitterConfiguration::default();

        $this->strategies = $configuration->getStrategies();
        $this->fallbackStrategies = $configuration->getFallbackStrategies();
        $this->externalStrategies = $configuration->getExternalStrategies();
        $this->useExternalStrategies = $configuration->isExternalStrategiesEnabled();
        $this->resultSelector = new BestResultSelector;
    }

    public function split(string $address, ?string $countryCode = null): ParsedAddress
    {
        $address = mb_trim($address);

        if ($address === '') {
            return new ParsedAddress(street: '');
        }

        // 1. Try country-specific or fallback strategies (collect all → BestResultSelector)
        $strategies = $this->resolveStrategies($countryCode);
        $results = $this->collectResults($strategies, $address);

        $bestResult = $this->resultSelector->select($results);

        if ($bestResult !== null) {
            return $bestResult;
        }

        // 2. Try external strategies (first match wins - to minimize API costs)
        if ($this->useExternalStrategies) {
            $externalResult = $this->tryExternalStrategies($address);

            if ($externalResult !== null) {
                return $externalResult;
            }
        }

        // 3. Return raw address as fallback
        return new ParsedAddress(street: $address);
    }

    /**
     * @param  list<AddressParsingStrategyInterface>  $strategies
     * @return list<ParsedAddress>
     */
    private function collectResults(array $strategies, string $address): array
    {
        $results = [];

        foreach ($strategies as $strategy) {
            try {
                $results[] = $strategy->parse($address);
            } catch (CannotParseAddressException) {
                continue;
            }
        }

        return $results;
    }

    /**
     * Try external strategies with "first match wins" logic.
     * Stops at first successful result to minimize API costs.
     */
    private function tryExternalStrategies(string $address): ?ParsedAddress
    {
        foreach ($this->externalStrategies as $strategy) {
            try {
                return $strategy->parse($address);
            } catch (CannotParseAddressException) {
                continue;
            }
        }

        return null;
    }

    /**
     * @return list<AddressParsingStrategyInterface>
     */
    private function resolveStrategies(?string $countryCode): array
    {
        if ($countryCode === null || $countryCode === '') {
            return $this->fallbackStrategies;
        }

        $countryCode = mb_strtoupper($countryCode);

        return $this->strategies[$countryCode] ?? $this->fallbackStrategies;
    }
}
