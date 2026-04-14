<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Hungary (HU)', function (): void {
    it('parses Váci utca 12', function (): void {
        $result = $this->service->split('Váci utca 12', 'HU');

        expect($result->street)->toBe('Váci utca')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses abbreviated street', function (): void {
        $result = $this->service->split('Váci u. 12', 'HU');

        expect($result->street)->toBe('Váci u.')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Andrássy út', function (): void {
        $result = $this->service->split('Andrássy út 60', 'HU');

        expect($result->street)->toBe('Andrássy út')
            ->and($result->buildingNumber)->toBe('60');
    });

    it('parses with dot-separated floor info', function (): void {
        $result = $this->service->split('Váci u. 12. III/5', 'HU');

        expect($result->street)->toBe('Váci u.')
            ->and($result->buildingNumber)->toBe('12')
            ->and($result->apartmentNumber)->toBe('III/5');
    });

    it('parses building range', function (): void {
        $result = $this->service->split('Kossuth tér 1-3', 'HU');

        expect($result->street)->toBe('Kossuth tér')
            ->and($result->buildingNumber)->toBe('1-3');
    });

    // --- Street type variants ---

    it('parses köz type', function (): void {
        $result = $this->service->split('Szép köz 3', 'HU');

        expect($result->street)->toBe('Szép köz')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses körút type', function (): void {
        $result = $this->service->split('Erzsébet körút 15', 'HU');

        expect($result->street)->toBe('Erzsébet körút')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses krt. abbreviation', function (): void {
        $result = $this->service->split('Erzsébet krt. 15', 'HU');

        expect($result->street)->toBe('Erzsébet krt.')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses fasor type', function (): void {
        $result = $this->service->split('Városligeti fasor 24', 'HU');

        expect($result->street)->toBe('Városligeti fasor')
            ->and($result->buildingNumber)->toBe('24');
    });

    it('parses rakpart type', function (): void {
        $result = $this->service->split('Széchenyi rakpart 5', 'HU');

        expect($result->street)->toBe('Széchenyi rakpart')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses setány type', function (): void {
        $result = $this->service->split('Dózsa György setány 18', 'HU');

        expect($result->street)->toBe('Dózsa György setány')
            ->and($result->buildingNumber)->toBe('18');
    });

    // --- Apartment / floor variants ---

    it('parses trailing dot with fszt', function (): void {
        $result = $this->service->split('Petőfi u. 8. fszt. 2', 'HU');

        expect($result->street)->toBe('Petőfi u.')
            ->and($result->buildingNumber)->toBe('8')
            ->and($result->apartmentNumber)->toBe('fszt. 2');
    });

    it('parses trailing dot with emelet/ajtó', function (): void {
        $result = $this->service->split('Kossuth u. 12. 3. em. 5. ajtó', 'HU');

        expect($result->street)->toBe('Kossuth u.')
            ->and($result->buildingNumber)->toBe('12')
            ->and($result->apartmentNumber)->toBe('3. em. 5. ajtó');
    });

    it('parses trailing dot with emelet abbreviation', function (): void {
        $result = $this->service->split('Bartók Béla út 15. 3. em.', 'HU');

        expect($result->street)->toBe('Bartók Béla út')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3. em.');
    });

    it('parses trailing dot with Roman II floor', function (): void {
        $result = $this->service->split('Rákóczi út 42. II/7', 'HU');

        expect($result->street)->toBe('Rákóczi út')
            ->and($result->buildingNumber)->toBe('42')
            ->and($result->apartmentNumber)->toBe('II/7');
    });

    // --- Other patterns ---

    it('parses hyphenated street name', function (): void {
        $result = $this->service->split('Bajcsy-Zsilinszky út 31', 'HU');

        expect($result->street)->toBe('Bajcsy-Zsilinszky út')
            ->and($result->buildingNumber)->toBe('31');
    });

    it('parses building number with letter suffix', function (): void {
        $result = $this->service->split('Vörösmarty tér 5A', 'HU');

        expect($result->street)->toBe('Vörösmarty tér')
            ->and($result->buildingNumber)->toBe('5A');
    });

    it('parses larger building range', function (): void {
        $result = $this->service->split('Múzeum körút 7-9', 'HU');

        expect($result->street)->toBe('Múzeum körút')
            ->and($result->buildingNumber)->toBe('7-9');
    });
});
