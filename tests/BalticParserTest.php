<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Lithuania (LT)', function (): void {
    it('parses Gedimino pr. 15', function (): void {
        $result = $this->service->split('Gedimino pr. 15', 'LT');

        expect($result->street)->toBe('Gedimino pr.')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with dash apartment', function (): void {
        $result = $this->service->split('Gedimino pr. 15-3', 'LT');

        expect($result->street)->toBe('Gedimino pr.')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses full gatvė suffix', function (): void {
        $result = $this->service->split('Vilniaus gatvė 22', 'LT');

        expect($result->street)->toBe('Vilniaus gatvė')
            ->and($result->buildingNumber)->toBe('22');
    });

    it('parses g. abbreviation', function (): void {
        $result = $this->service->split('Vilniaus g. 22-5', 'LT');

        expect($result->street)->toBe('Vilniaus g.')
            ->and($result->buildingNumber)->toBe('22')
            ->and($result->apartmentNumber)->toBe('5');
    });

    it('parses al. (alėja) type', function (): void {
        $result = $this->service->split('Laisvės al. 60', 'LT');

        expect($result->street)->toBe('Laisvės al.')
            ->and($result->buildingNumber)->toBe('60');
    });

    it('parses with bt. separator', function (): void {
        $result = $this->service->split('Gedimino pr. 20, bt. 8', 'LT');

        expect($result->street)->toBe('Gedimino pr.')
            ->and($result->buildingNumber)->toBe('20')
            ->and($result->apartmentNumber)->toBe('8');
    });
});

describe('Latvia (LV)', function (): void {
    it('parses Latvian address with dash', function (): void {
        $result = $this->service->split('Brīvības iela 15-3', 'LV');

        expect($result->street)->toBe('Brīvības iela')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses with dz. separator', function (): void {
        $result = $this->service->split('Brīvības iela 15, dz. 3', 'LV');

        expect($result->street)->toBe('Brīvības iela')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses bulvāris type', function (): void {
        $result = $this->service->split('Aspazijas bulvāris 24', 'LV');

        expect($result->street)->toBe('Aspazijas bulvāris')
            ->and($result->buildingNumber)->toBe('24');
    });

    it('parses prospekts type', function (): void {
        $result = $this->service->split('Viesturdārzs prospekts 10-2', 'LV');

        expect($result->street)->toBe('Viesturdārzs prospekts')
            ->and($result->buildingNumber)->toBe('10')
            ->and($result->apartmentNumber)->toBe('2');
    });
});

describe('Estonia (EE)', function (): void {
    it('parses Estonian address', function (): void {
        $result = $this->service->split('Viru 15-3', 'EE');

        expect($result->street)->toBe('Viru')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses tänav suffix', function (): void {
        $result = $this->service->split('Narva maantee 7', 'EE');

        expect($result->street)->toBe('Narva maantee')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses puiestee suffix', function (): void {
        $result = $this->service->split('Pärnu puiestee 67', 'EE');

        expect($result->street)->toBe('Pärnu puiestee')
            ->and($result->buildingNumber)->toBe('67');
    });

    it('parses with krt separator', function (): void {
        $result = $this->service->split('Viru 15, krt 3', 'EE');

        expect($result->street)->toBe('Viru')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses building number with letter', function (): void {
        $result = $this->service->split('Pikk 12A', 'EE');

        expect($result->street)->toBe('Pikk')
            ->and($result->buildingNumber)->toBe('12A');
    });
});
