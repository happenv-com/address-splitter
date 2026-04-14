<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Romania (RO)', function (): void {
    it('parses Str. Victoriei 15', function (): void {
        $result = $this->service->split('Str. Victoriei 15', 'RO');

        expect($result->street)->toBe('Str. Victoriei')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with nr. prefix', function (): void {
        $result = $this->service->split('Str. Victoriei nr. 15', 'RO');

        expect($result->street)->toBe('Str. Victoriei')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with apartment info', function (): void {
        $result = $this->service->split('Str. Victoriei nr. 15, ap. 3', 'RO');

        expect($result->street)->toBe('Str. Victoriei')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('ap. 3');
    });

    it('parses complex Romanian address', function (): void {
        $result = $this->service->split('Bd. Unirii 1, bl. B3, ap. 45', 'RO');

        expect($result->street)->toBe('Bd. Unirii')
            ->and($result->buildingNumber)->toBe('1')
            ->and($result->apartmentNumber)->toBe('bl. B3, ap. 45');
    });

    // --- Street type variants ---

    it('parses full Strada prefix', function (): void {
        $result = $this->service->split('Strada Lipscani 42', 'RO');

        expect($result->street)->toBe('Strada Lipscani')
            ->and($result->buildingNumber)->toBe('42');
    });

    it('parses Calea type', function (): void {
        $result = $this->service->split('Calea Victoriei 120', 'RO');

        expect($result->street)->toBe('Calea Victoriei')
            ->and($result->buildingNumber)->toBe('120');
    });

    it('parses Aleea type', function (): void {
        $result = $this->service->split('Aleea Teișani 5', 'RO');

        expect($result->street)->toBe('Aleea Teișani')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Piața type', function (): void {
        $result = $this->service->split('Piața Revoluției 1', 'RO');

        expect($result->street)->toBe('Piața Revoluției')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Șoseaua type', function (): void {
        $result = $this->service->split('Șoseaua Nordului 88', 'RO');

        expect($result->street)->toBe('Șoseaua Nordului')
            ->and($result->buildingNumber)->toBe('88');
    });

    it('parses Șos. abbreviation', function (): void {
        $result = $this->service->split('Șos. Kiseleff 30', 'RO');

        expect($result->street)->toBe('Șos. Kiseleff')
            ->and($result->buildingNumber)->toBe('30');
    });

    it('parses Splaiul type', function (): void {
        $result = $this->service->split('Splaiul Independenței 202', 'RO');

        expect($result->street)->toBe('Splaiul Independenței')
            ->and($result->buildingNumber)->toBe('202');
    });

    it('parses Intrarea type', function (): void {
        $result = $this->service->split('Intrarea Băiculești 5', 'RO');

        expect($result->street)->toBe('Intrarea Băiculești')
            ->and($result->buildingNumber)->toBe('5');
    });

    // --- Complex apartment patterns ---

    it('parses full complex: nr + bl + sc + et + ap', function (): void {
        $result = $this->service->split('Str. Victoriei nr. 15, bl. A, sc. 2, et. 3, ap. 12', 'RO');

        expect($result->street)->toBe('Str. Victoriei')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('bl. A, sc. 2, et. 3, ap. 12');
    });

    it('parses with et. and ap. only', function (): void {
        $result = $this->service->split('Calea Dorobanților 50, et. 2, ap. 8', 'RO');

        expect($result->street)->toBe('Calea Dorobanților')
            ->and($result->buildingNumber)->toBe('50')
            ->and($result->apartmentNumber)->toBe('et. 2, ap. 8');
    });

    it('parses full Bulevardul prefix', function (): void {
        $result = $this->service->split('Bulevardul Magheru nr. 28', 'RO');

        expect($result->street)->toBe('Bulevardul Magheru')
            ->and($result->buildingNumber)->toBe('28');
    });

    it('parses building number with letter', function (): void {
        $result = $this->service->split('Str. Smârdan 30A', 'RO');

        expect($result->street)->toBe('Str. Smârdan')
            ->and($result->buildingNumber)->toBe('30A');
    });
});
