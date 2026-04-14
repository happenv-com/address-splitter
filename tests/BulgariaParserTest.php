<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Bulgaria (BG)', function (): void {
    it('parses Cyrillic address', function (): void {
        $result = $this->service->split('ул. Витоша 15', 'BG');

        expect($result->street)->toBe('ул. Витоша')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with apartment', function (): void {
        $result = $this->service->split('ул. Витоша 15, ап. 3', 'BG');

        expect($result->street)->toBe('ул. Витоша')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('ап. 3');
    });

    it('parses transliterated address', function (): void {
        $result = $this->service->split('ul. Vitosha 15', 'BG');

        expect($result->street)->toBe('ul. Vitosha')
            ->and($result->buildingNumber)->toBe('15');
    });

    // --- Street type variants ---

    it('parses бул. (boulevard) type', function (): void {
        $result = $this->service->split('бул. Цар Освободител 12', 'BG');

        expect($result->street)->toBe('бул. Цар Освободител')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses пл. (square) type', function (): void {
        $result = $this->service->split('пл. Народно Събрание 5', 'BG');

        expect($result->street)->toBe('пл. Народно Събрание')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses transliterated bul. type', function (): void {
        $result = $this->service->split('bul. Tsar Osvoboditel 12', 'BG');

        expect($result->street)->toBe('bul. Tsar Osvoboditel')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses full улица type', function (): void {
        $result = $this->service->split('улица Раковски 108', 'BG');

        expect($result->street)->toBe('улица Раковски')
            ->and($result->buildingNumber)->toBe('108');
    });

    // --- Apartment / block variants ---

    it('parses with бл. and ап.', function (): void {
        $result = $this->service->split('ул. Шипка 34, бл. 2, ап. 15', 'BG');

        expect($result->street)->toBe('ул. Шипка')
            ->and($result->buildingNumber)->toBe('34')
            ->and($result->apartmentNumber)->toBe('бл. 2, ап. 15');
    });

    it('parses with вх. and ет. and ап.', function (): void {
        $result = $this->service->split('ул. Витоша 100, вх. А, ет. 3, ап. 12', 'BG');

        expect($result->street)->toBe('ул. Витоша')
            ->and($result->buildingNumber)->toBe('100')
            ->and($result->apartmentNumber)->toBe('вх. А, ет. 3, ап. 12');
    });

    it('parses building with Cyrillic letter suffix', function (): void {
        $result = $this->service->split('ул. Граф Игнатиев 7А', 'BG');

        expect($result->street)->toBe('ул. Граф Игнатиев')
            ->and($result->buildingNumber)->toBe('7А');
    });

    it('parses transliterated with apartment', function (): void {
        $result = $this->service->split('ul. Rakovski 108, ap. 5', 'BG');

        expect($result->street)->toBe('ul. Rakovski')
            ->and($result->buildingNumber)->toBe('108')
            ->and($result->apartmentNumber)->toBe('ap. 5');
    });
});
