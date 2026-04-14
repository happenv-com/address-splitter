<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('United Kingdom (GB)', function (): void {
    it('parses number before street', function (): void {
        $result = $this->service->split('15 High Street', 'GB');

        expect($result->street)->toBe('High Street')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with letter suffix', function (): void {
        $result = $this->service->split('15a High Street', 'GB');

        expect($result->street)->toBe('High Street')
            ->and($result->buildingNumber)->toBe('15a');
    });

    it('parses Flat prefix', function (): void {
        $result = $this->service->split('Flat 3, 15 High Street', 'GB');

        expect($result->street)->toBe('High Street')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses Flat suffix', function (): void {
        $result = $this->service->split('15 High Street, Flat 3', 'GB');

        expect($result->street)->toBe('High Street')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses building range', function (): void {
        $result = $this->service->split('12-14 Oxford Street', 'GB');

        expect($result->street)->toBe('Oxford Street')
            ->and($result->buildingNumber)->toBe('12-14');
    });

    it('parses Unit prefix', function (): void {
        $result = $this->service->split("Unit 4, 20 King's Road", 'GB');

        expect($result->street)->toBe("King's Road")
            ->and($result->buildingNumber)->toBe('20')
            ->and($result->apartmentNumber)->toBe('4');
    });
});

describe('Ireland (IE)', function (): void {
    it('uses British parser for Irish address', function (): void {
        $result = $this->service->split("42 O'Connell Street", 'IE');

        expect($result->street)->toBe("O'Connell Street")
            ->and($result->buildingNumber)->toBe('42');
    });
});
