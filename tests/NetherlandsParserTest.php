<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Netherlands (NL)', function (): void {
    it('parses Keizersgracht 123', function (): void {
        $result = $this->service->split('Keizersgracht 123', 'NL');

        expect($result->street)->toBe('Keizersgracht')
            ->and($result->buildingNumber)->toBe('123');
    });

    it('parses with dash-separated apartment', function (): void {
        $result = $this->service->split('Keizersgracht 123-II', 'NL');

        expect($result->street)->toBe('Keizersgracht')
            ->and($result->buildingNumber)->toBe('123')
            ->and($result->apartmentNumber)->toBe('II');
    });

    it('parses with letter suffix', function (): void {
        $result = $this->service->split('Keizersgracht 123 A', 'NL');

        expect($result->street)->toBe('Keizersgracht')
            ->and($result->buildingNumber)->toBe('123A');
    });

    it('parses with hs apartment', function (): void {
        $result = $this->service->split('Prinsengracht 263-hs', 'NL');

        expect($result->street)->toBe('Prinsengracht')
            ->and($result->buildingNumber)->toBe('263')
            ->and($result->apartmentNumber)->toBe('hs');
    });

    it('parses Lange Voorhout', function (): void {
        $result = $this->service->split('Lange Voorhout 8', 'NL');

        expect($result->street)->toBe('Lange Voorhout')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses ordinal street prefix', function (): void {
        $result = $this->service->split('2e Constantijn Huygensstraat 10', 'NL');

        expect($result->street)->toBe('2e Constantijn Huygensstraat')
            ->and($result->buildingNumber)->toBe('10');
    });

    // --- Dutch street suffix types ---

    it('parses Damstraat with letter suffix', function (): void {
        $result = $this->service->split('Damstraat 45B', 'NL');

        expect($result->street)->toBe('Damstraat')
            ->and($result->buildingNumber)->toBe('45B');
    });

    it('parses -laan suffix: Apollolaan', function (): void {
        $result = $this->service->split('Apollolaan 171', 'NL');

        expect($result->street)->toBe('Apollolaan')
            ->and($result->buildingNumber)->toBe('171');
    });

    it('parses -weg suffix: Kruisweg', function (): void {
        $result = $this->service->split('Kruisweg 15', 'NL');

        expect($result->street)->toBe('Kruisweg')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses -plein suffix: Leidseplein', function (): void {
        $result = $this->service->split('Leidseplein 26', 'NL');

        expect($result->street)->toBe('Leidseplein')
            ->and($result->buildingNumber)->toBe('26');
    });

    it('parses Singel (canal name)', function (): void {
        $result = $this->service->split('Singel 140', 'NL');

        expect($result->street)->toBe('Singel')
            ->and($result->buildingNumber)->toBe('140');
    });

    it('parses -dijk suffix: Nieuwendijk', function (): void {
        $result = $this->service->split('Nieuwendijk 25', 'NL');

        expect($result->street)->toBe('Nieuwendijk')
            ->and($result->buildingNumber)->toBe('25');
    });

    it('parses -steeg suffix: Molensteeg', function (): void {
        $result = $this->service->split('Molensteeg 3', 'NL');

        expect($result->street)->toBe('Molensteeg')
            ->and($result->buildingNumber)->toBe('3');
    });

    // --- Apartment / floor variants ---

    it('parses dash with numeric apartment', function (): void {
        $result = $this->service->split('Herengracht 100-2', 'NL');

        expect($result->street)->toBe('Herengracht')
            ->and($result->buildingNumber)->toBe('100')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses dash with Roman numeral III', function (): void {
        $result = $this->service->split('Keizersgracht 174-III', 'NL');

        expect($result->street)->toBe('Keizersgracht')
            ->and($result->buildingNumber)->toBe('174')
            ->and($result->apartmentNumber)->toBe('III');
    });

    it('parses dash with huis suffix', function (): void {
        $result = $this->service->split('Herengracht 401-huis', 'NL');

        expect($result->street)->toBe('Herengracht')
            ->and($result->buildingNumber)->toBe('401')
            ->and($result->apartmentNumber)->toBe('huis');
    });

    // --- Ordinal prefix variants ---

    it('parses 1ste ordinal prefix', function (): void {
        $result = $this->service->split('1ste Helmersstraat 33', 'NL');

        expect($result->street)->toBe('1ste Helmersstraat')
            ->and($result->buildingNumber)->toBe('33');
    });

    it('parses 3de ordinal prefix', function (): void {
        $result = $this->service->split('3de Kostverlorenkade 18', 'NL');

        expect($result->street)->toBe('3de Kostverlorenkade')
            ->and($result->buildingNumber)->toBe('18');
    });

    // --- Multi-word with Dutch prepositions ---

    it('parses street with van preposition', function (): void {
        $result = $this->service->split('Jan van Galenstraat 109', 'NL');

        expect($result->street)->toBe('Jan van Galenstraat')
            ->and($result->buildingNumber)->toBe('109');
    });

    it('parses street with de preposition', function (): void {
        $result = $this->service->split('Pieter de Hoochstraat 50', 'NL');

        expect($result->street)->toBe('Pieter de Hoochstraat')
            ->and($result->buildingNumber)->toBe('50');
    });

    it('parses long compound street name', function (): void {
        $result = $this->service->split('Sint Antoniesbreestraat 69', 'NL');

        expect($result->street)->toBe('Sint Antoniesbreestraat')
            ->and($result->buildingNumber)->toBe('69');
    });

    it('parses Damrak (no standard suffix)', function (): void {
        $result = $this->service->split('Damrak 1', 'NL');

        expect($result->street)->toBe('Damrak')
            ->and($result->buildingNumber)->toBe('1');
    });
});
