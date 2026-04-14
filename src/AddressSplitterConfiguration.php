<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter;

use Happenv\AddressSplitter\Contracts\AddressParsingStrategyInterface;
use Happenv\AddressSplitter\GenericParsers\CommaApartmentStrategy;
use Happenv\AddressSplitter\GenericParsers\DashApartmentStrategy;
use Happenv\AddressSplitter\GenericParsers\NumberFirstStrategy as GenericNumberFirstStrategy;
use Happenv\AddressSplitter\GenericParsers\SlashApartmentStrategy;
use Happenv\AddressSplitter\GenericParsers\StreetFirstStrategy as GenericStreetFirstStrategy;
use Happenv\AddressSplitter\Parsers\BalticParser\BalticStrategy;
use Happenv\AddressSplitter\Parsers\BritishParser\FlatPrefixStrategy as BritishFlatPrefixStrategy;
use Happenv\AddressSplitter\Parsers\BritishParser\FlatSuffixStrategy as BritishFlatSuffixStrategy;
use Happenv\AddressSplitter\Parsers\BritishParser\NumberFirstStrategy as BritishNumberFirstStrategy;
use Happenv\AddressSplitter\Parsers\BritishParser\StreetFirstStrategy as BritishStreetFirstStrategy;
use Happenv\AddressSplitter\Parsers\BulgariaParser\BulgarianStrategy;
use Happenv\AddressSplitter\Parsers\CroatiaSloveniaParser\CroatiaSloveniaStrategy;
use Happenv\AddressSplitter\Parsers\CzechSlovakParser\DualNumberStrategy;
use Happenv\AddressSplitter\Parsers\CzechSlovakParser\PrefixDualNumberStrategy;
use Happenv\AddressSplitter\Parsers\CzechSlovakParser\SimpleNumberStrategy;
use Happenv\AddressSplitter\Parsers\FrenchParser\NumberFirstStrategy as FrenchNumberFirstStrategy;
use Happenv\AddressSplitter\Parsers\FrenchParser\StreetFirstStrategy as FrenchStreetFirstStrategy;
use Happenv\AddressSplitter\Parsers\FrenchParser\StreetTypeStrategy as FrenchStreetTypeStrategy;
use Happenv\AddressSplitter\Parsers\GermanicParser\GermanicStrategy;
use Happenv\AddressSplitter\Parsers\GreeceParser\GreeceStrategy;
use Happenv\AddressSplitter\Parsers\HungaryParser\StandardHungarianStrategy;
use Happenv\AddressSplitter\Parsers\HungaryParser\TrailingDotFloorStrategy;
use Happenv\AddressSplitter\Parsers\ItalyParser\GenericItalianStrategy;
use Happenv\AddressSplitter\Parsers\ItalyParser\StreetTypeStrategy as ItalyStreetTypeStrategy;
use Happenv\AddressSplitter\Parsers\MaltaCyprusParser\NumberFirstStrategy as MaltaCyprusNumberFirstStrategy;
use Happenv\AddressSplitter\Parsers\MaltaCyprusParser\StreetFirstStrategy as MaltaCyprusStreetFirstStrategy;
use Happenv\AddressSplitter\Parsers\NetherlandsParser\BusSeparatorStrategy;
use Happenv\AddressSplitter\Parsers\NetherlandsParser\OrdinalPrefixStrategy;
use Happenv\AddressSplitter\Parsers\NetherlandsParser\StandardDutchStrategy;
use Happenv\AddressSplitter\Parsers\NordicParser\FinnishStyleStrategy;
use Happenv\AddressSplitter\Parsers\NordicParser\FloorInfoStrategy;
use Happenv\AddressSplitter\Parsers\NordicParser\StandardNordicStrategy;
use Happenv\AddressSplitter\Parsers\PolandParser\DateStreetStrategy;
use Happenv\AddressSplitter\Parsers\PolandParser\GenericPolishStrategy;
use Happenv\AddressSplitter\Parsers\PolandParser\PrefixedStreetStrategy;
use Happenv\AddressSplitter\Parsers\PolandParser\RomanNumeralStreetStrategy;
use Happenv\AddressSplitter\Parsers\PortugalParser\GenericPortugueseStrategy;
use Happenv\AddressSplitter\Parsers\PortugalParser\StreetTypeStrategy as PortugalStreetTypeStrategy;
use Happenv\AddressSplitter\Parsers\RomaniaParser\NrPrefixStrategy;
use Happenv\AddressSplitter\Parsers\RomaniaParser\StandardRomanianStrategy;
use Happenv\AddressSplitter\Parsers\SpainParser\GenericSpanishStrategy;
use Happenv\AddressSplitter\Parsers\SpainParser\StreetTypeStrategy as SpainStreetTypeStrategy;

final class AddressSplitterConfiguration
{
    /**
     * @var array<string, list<AddressParsingStrategyInterface>>
     */
    private array $strategies = [];

    /**
     * @var list<AddressParsingStrategyInterface>
     */
    private array $fallbackStrategies = [];

    /**
     * @var list<AddressParsingStrategyInterface>
     */
    private array $externalStrategies = [];

    private bool $useExternalStrategies = false;

    public function __construct()
    {
        $this->fallbackStrategies = self::defaultFallbackStrategies();
    }

    /**
     * Creates a configuration with all default strategies.
     */
    public static function default(): self
    {
        return (new self)
            ->append('PL', [
                new PrefixedStreetStrategy,
                new DateStreetStrategy,
                new RomanNumeralStreetStrategy,
                new GenericPolishStrategy,
            ])
            ->append('DE', [new GermanicStrategy])
            ->append('AT', [new GermanicStrategy])
            ->append('CH', [new GermanicStrategy])
            ->append('FR', [
                new FrenchStreetTypeStrategy,
                new FrenchNumberFirstStrategy,
                new FrenchStreetFirstStrategy,
            ])
            ->append('BE', [
                new FrenchStreetTypeStrategy,
                new FrenchNumberFirstStrategy,
                new FrenchStreetFirstStrategy,
            ])
            ->append('LU', [
                new FrenchStreetTypeStrategy,
                new FrenchNumberFirstStrategy,
                new FrenchStreetFirstStrategy,
            ])
            ->append('NL', [
                new OrdinalPrefixStrategy,
                new StandardDutchStrategy,
                new BusSeparatorStrategy,
            ])
            ->append('IT', [
                new ItalyStreetTypeStrategy,
                new GenericItalianStrategy,
            ])
            ->append('ES', [
                new SpainStreetTypeStrategy,
                new GenericSpanishStrategy,
            ])
            ->append('PT', [
                new PortugalStreetTypeStrategy,
                new GenericPortugueseStrategy,
            ])
            ->append('GB', [
                new BritishFlatPrefixStrategy,
                new BritishFlatSuffixStrategy,
                new BritishNumberFirstStrategy,
                new BritishStreetFirstStrategy,
            ])
            ->append('IE', [
                new BritishFlatPrefixStrategy,
                new BritishFlatSuffixStrategy,
                new BritishNumberFirstStrategy,
                new BritishStreetFirstStrategy,
            ])
            ->append('SE', [
                new FloorInfoStrategy,
                new StandardNordicStrategy,
                new FinnishStyleStrategy,
            ])
            ->append('FI', [
                new FloorInfoStrategy,
                new StandardNordicStrategy,
                new FinnishStyleStrategy,
            ])
            ->append('DK', [
                new FloorInfoStrategy,
                new StandardNordicStrategy,
                new FinnishStyleStrategy,
            ])
            ->append('CZ', [
                new PrefixDualNumberStrategy,
                new DualNumberStrategy,
                new SimpleNumberStrategy,
            ])
            ->append('SK', [
                new PrefixDualNumberStrategy,
                new DualNumberStrategy,
                new SimpleNumberStrategy,
            ])
            ->append('HU', [
                new TrailingDotFloorStrategy,
                new StandardHungarianStrategy,
            ])
            ->append('LT', [new BalticStrategy])
            ->append('LV', [new BalticStrategy])
            ->append('EE', [new BalticStrategy])
            ->append('RO', [
                new NrPrefixStrategy,
                new StandardRomanianStrategy,
            ])
            ->append('BG', [new BulgarianStrategy])
            ->append('HR', [new CroatiaSloveniaStrategy])
            ->append('SI', [new CroatiaSloveniaStrategy])
            ->append('GR', [new GreeceStrategy])
            ->append('MT', [
                new MaltaCyprusNumberFirstStrategy,
                new MaltaCyprusStreetFirstStrategy,
            ])
            ->append('CY', [
                new MaltaCyprusNumberFirstStrategy,
                new MaltaCyprusStreetFirstStrategy,
            ]);
    }

    /**
     * Creates an empty configuration with no strategies.
     */
    public static function empty(): self
    {
        $config = new self;
        $config->fallbackStrategies = [];

        return $config;
    }

    /**
     * Default fallback strategies used when no country-specific strategy matches.
     *
     * @return list<AddressParsingStrategyInterface>
     */
    public static function defaultFallbackStrategies(): array
    {
        return [
            new SlashApartmentStrategy,
            new CommaApartmentStrategy,
            new DashApartmentStrategy,
            new GenericStreetFirstStrategy,
            new GenericNumberFirstStrategy,
        ];
    }

    /**
     * Add strategies at the end for a country (lower priority than existing strategies).
     *
     * @param  string  $countryCode  ISO 3166-1 alpha-2 country code
     * @param  list<AddressParsingStrategyInterface>  $strategies
     */
    public function append(string $countryCode, array $strategies): self
    {
        $countryCode = mb_strtoupper($countryCode);

        if (! isset($this->strategies[$countryCode])) {
            $this->strategies[$countryCode] = [];
        }

        $this->strategies[$countryCode] = array_merge($this->strategies[$countryCode], $strategies);

        return $this;
    }

    /**
     * Add strategies at the beginning for a country (higher priority than existing strategies).
     *
     * @param  string  $countryCode  ISO 3166-1 alpha-2 country code
     * @param  list<AddressParsingStrategyInterface>  $strategies
     */
    public function prepend(string $countryCode, array $strategies): self
    {
        $countryCode = mb_strtoupper($countryCode);

        if (! isset($this->strategies[$countryCode])) {
            $this->strategies[$countryCode] = [];
        }

        $this->strategies[$countryCode] = array_merge($strategies, $this->strategies[$countryCode]);

        return $this;
    }

    /**
     * Set the fallback strategies used when no country-specific strategy matches.
     *
     * @param  list<AddressParsingStrategyInterface>  $strategies
     */
    public function withFallbackStrategies(array $strategies): self
    {
        $this->fallbackStrategies = $strategies;

        return $this;
    }

    /**
     * @return array<string, list<AddressParsingStrategyInterface>>
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * @return list<AddressParsingStrategyInterface>
     */
    public function getStrategiesForCountry(string $countryCode): array
    {
        $countryCode = mb_strtoupper($countryCode);

        return $this->strategies[$countryCode] ?? [];
    }

    /**
     * @return list<AddressParsingStrategyInterface>
     */
    public function getFallbackStrategies(): array
    {
        return $this->fallbackStrategies;
    }

    /**
     * Set external strategies (API-based, paid services).
     * These are used as last resort with "first match wins" logic.
     *
     * @param  list<AddressParsingStrategyInterface>  $strategies
     */
    public function withExternalStrategies(array $strategies): self
    {
        $this->externalStrategies = $strategies;
        $this->useExternalStrategies = true;

        return $this;
    }

    /**
     * Disable external strategies.
     */
    public function disableExternalStrategies(): self
    {
        $this->useExternalStrategies = false;

        return $this;
    }

    /**
     * Enable external strategies.
     */
    public function enableExternalStrategies(): self
    {
        $this->useExternalStrategies = true;

        return $this;
    }

    /**
     * @return list<AddressParsingStrategyInterface>
     */
    public function getExternalStrategies(): array
    {
        return $this->externalStrategies;
    }

    public function isExternalStrategiesEnabled(): bool
    {
        return $this->useExternalStrategies;
    }
}
