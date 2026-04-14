<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Croatia (HR)', function (): void {
    it('parses Ilica 15', function (): void {
        $result = $this->service->split('Ilica 15', 'HR');

        expect($result->street)->toBe('Ilica')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with slash apartment', function (): void {
        $result = $this->service->split('Ilica 15/3', 'HR');

        expect($result->street)->toBe('Ilica')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses Trg bana Jelačića', function (): void {
        $result = $this->service->split('Trg bana Jelačića 1', 'HR');

        expect($result->street)->toBe('Trg bana Jelačića')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses ulica type', function (): void {
        $result = $this->service->split('Tratinska ulica 44', 'HR');

        expect($result->street)->toBe('Tratinska ulica')
            ->and($result->buildingNumber)->toBe('44');
    });

    it('parses ul. abbreviation', function (): void {
        $result = $this->service->split('Frankopanska ul. 8', 'HR');

        expect($result->street)->toBe('Frankopanska ul.')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses cesta type', function (): void {
        $result = $this->service->split('Vukovarska cesta 220', 'HR');

        expect($result->street)->toBe('Vukovarska cesta')
            ->and($result->buildingNumber)->toBe('220');
    });

    it('parses building letter suffix', function (): void {
        $result = $this->service->split('Ilica 72a', 'HR');

        expect($result->street)->toBe('Ilica')
            ->and($result->buildingNumber)->toBe('72a');
    });

    it('parses put type', function (): void {
        $result = $this->service->split('Savski put 15', 'HR');

        expect($result->street)->toBe('Savski put')
            ->and($result->buildingNumber)->toBe('15');
    });
});

describe('Slovenia (SI)', function (): void {
    it('parses Slovenian address', function (): void {
        $result = $this->service->split('Prešernova cesta 12', 'SI');

        expect($result->street)->toBe('Prešernova cesta')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses with letter suffix', function (): void {
        $result = $this->service->split('Slovenska cesta 55a', 'SI');

        expect($result->street)->toBe('Slovenska cesta')
            ->and($result->buildingNumber)->toBe('55a');
    });

    it('parses ulica type', function (): void {
        $result = $this->service->split('Dunajska ulica 101', 'SI');

        expect($result->street)->toBe('Dunajska ulica')
            ->and($result->buildingNumber)->toBe('101');
    });

    it('parses trg type', function (): void {
        $result = $this->service->split('Prešernov trg 4', 'SI');

        expect($result->street)->toBe('Prešernov trg')
            ->and($result->buildingNumber)->toBe('4');
    });

    it('parses pot type', function (): void {
        $result = $this->service->split('Tržaška pot 29', 'SI');

        expect($result->street)->toBe('Tržaška pot')
            ->and($result->buildingNumber)->toBe('29');
    });

    it('parses with slash apartment', function (): void {
        $result = $this->service->split('Celovška cesta 150/8', 'SI');

        expect($result->street)->toBe('Celovška cesta')
            ->and($result->buildingNumber)->toBe('150')
            ->and($result->apartmentNumber)->toBe('8');
    });
});
