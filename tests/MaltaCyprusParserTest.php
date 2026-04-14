<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Malta (MT)', function (): void {
    it('parses English-style Maltese address', function (): void {
        $result = $this->service->split('15 Republic Street', 'MT');

        expect($result->street)->toBe('Republic Street')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Maltese-style address', function (): void {
        $result = $this->service->split('Triq ir-Repubblika 15', 'MT');

        expect($result->street)->toBe('Triq ir-Repubblika')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Triq il- prefix (Maltese)', function (): void {
        $result = $this->service->split('Triq il-Kbira 22', 'MT');

        expect($result->street)->toBe('Triq il-Kbira')
            ->and($result->buildingNumber)->toBe('22');
    });

    it('parses English-style with comma apartment', function (): void {
        $result = $this->service->split('15 Republic Street, Flat 3', 'MT');

        expect($result->street)->toBe('Republic Street')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('Flat 3');
    });

    it('parses Pjazza (square) type', function (): void {
        $result = $this->service->split('Pjazza San Ġorġ 1', 'MT');

        expect($result->street)->toBe('Pjazza San Ġorġ')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses English-style Avenue', function (): void {
        $result = $this->service->split('10 Tower Road', 'MT');

        expect($result->street)->toBe('Tower Road')
            ->and($result->buildingNumber)->toBe('10');
    });
});

describe('Cyprus (CY)', function (): void {
    it('parses Cypriot address', function (): void {
        $result = $this->service->split('Makariou 120', 'CY');

        expect($result->street)->toBe('Makariou')
            ->and($result->buildingNumber)->toBe('120');
    });

    it('parses English-style Kennedy Avenue', function (): void {
        $result = $this->service->split('15 Kennedy Avenue', 'CY');

        expect($result->street)->toBe('Kennedy Avenue')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Ledra Street', function (): void {
        $result = $this->service->split('Ledra Street 42', 'CY');

        expect($result->street)->toBe('Ledra Street')
            ->and($result->buildingNumber)->toBe('42');
    });

    it('parses with comma apartment info', function (): void {
        $result = $this->service->split('Makariou 80, Apt 5', 'CY');

        expect($result->street)->toBe('Makariou')
            ->and($result->buildingNumber)->toBe('80')
            ->and($result->apartmentNumber)->toBe('Apt 5');
    });

    it('parses multi-word Cypriot street', function (): void {
        $result = $this->service->split('Arch. Makarios III Avenue 50', 'CY');

        expect($result->street)->toBe('Arch. Makarios III Avenue')
            ->and($result->buildingNumber)->toBe('50');
    });
});
