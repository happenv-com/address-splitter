<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Sweden (SE)', function (): void {
    it('parses Storgatan 15', function (): void {
        $result = $this->service->split('Storgatan 15', 'SE');

        expect($result->street)->toBe('Storgatan')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with letter suffix', function (): void {
        $result = $this->service->split('Storgatan 15 B', 'SE');

        expect($result->street)->toBe('Storgatan')
            ->and($result->buildingNumber)->toBe('15B');
    });

    it('parses with lgh apartment', function (): void {
        $result = $this->service->split('Storgatan 15 B lgh 1203', 'SE');

        expect($result->street)->toBe('Storgatan')
            ->and($result->buildingNumber)->toBe('15B')
            ->and($result->apartmentNumber)->toBe('1203');
    });

    it('parses comma + trappa floor', function (): void {
        $result = $this->service->split('Storgatan 15, 2 tr', 'SE');

        expect($result->street)->toBe('Storgatan')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('2 tr');
    });

    it('parses letter suffix directly attached', function (): void {
        $result = $this->service->split('Kungsgatan 44A', 'SE');

        expect($result->street)->toBe('Kungsgatan')
            ->and($result->buildingNumber)->toBe('44A');
    });

    it('parses Sveavägen with letter + lgh', function (): void {
        $result = $this->service->split('Sveavägen 73 B lgh 1502', 'SE');

        expect($result->street)->toBe('Sveavägen')
            ->and($result->buildingNumber)->toBe('73B')
            ->and($result->apartmentNumber)->toBe('1502');
    });

    it('parses Drottninggatan with comma + trappa', function (): void {
        $result = $this->service->split('Drottninggatan 10, 3 tr', 'SE');

        expect($result->street)->toBe('Drottninggatan')
            ->and($result->buildingNumber)->toBe('10')
            ->and($result->apartmentNumber)->toBe('3 tr');
    });
});

describe('Finland (FI)', function (): void {
    it('parses Finnish address', function (): void {
        $result = $this->service->split('Mannerheimintie 5', 'FI');

        expect($result->street)->toBe('Mannerheimintie')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Aleksanterinkatu', function (): void {
        $result = $this->service->split('Aleksanterinkatu 48', 'FI');

        expect($result->street)->toBe('Aleksanterinkatu')
            ->and($result->buildingNumber)->toBe('48');
    });

    it('parses Hämeenkatu with letter suffix', function (): void {
        $result = $this->service->split('Hämeenkatu 19 B', 'FI');

        expect($result->street)->toBe('Hämeenkatu')
            ->and($result->buildingNumber)->toBe('19B');
    });
});

describe('Denmark (DK)', function (): void {
    it('parses Danish address', function (): void {
        $result = $this->service->split('Vestergade 10', 'DK');

        expect($result->street)->toBe('Vestergade')
            ->and($result->buildingNumber)->toBe('10');
    });

    it('parses with sal th floor info', function (): void {
        $result = $this->service->split('Vestergade 10, 3. sal th', 'DK');

        expect($result->street)->toBe('Vestergade')
            ->and($result->buildingNumber)->toBe('10')
            ->and($result->apartmentNumber)->toBe('3. sal th');
    });

    it('parses ground floor right: st. th', function (): void {
        $result = $this->service->split('Nørregade 22, st. th', 'DK');

        expect($result->street)->toBe('Nørregade')
            ->and($result->buildingNumber)->toBe('22')
            ->and($result->apartmentNumber)->toBe('st. th');
    });

    it('parses letter + 2. sal tv', function (): void {
        $result = $this->service->split('Bredgade 45 A, 2. sal tv', 'DK');

        expect($result->street)->toBe('Bredgade')
            ->and($result->buildingNumber)->toBe('45A')
            ->and($result->apartmentNumber)->toBe('2. sal tv');
    });

    it('parses Østerbrogade with 4. th', function (): void {
        $result = $this->service->split('Østerbrogade 100, 4. th', 'DK');

        expect($result->street)->toBe('Østerbrogade')
            ->and($result->buildingNumber)->toBe('100')
            ->and($result->apartmentNumber)->toBe('4. th');
    });

    it('parses ground floor only: st.', function (): void {
        $result = $this->service->split('Kongensgade 5, st.', 'DK');

        expect($result->street)->toBe('Kongensgade')
            ->and($result->buildingNumber)->toBe('5')
            ->and($result->apartmentNumber)->toBe('st.');
    });
});
