<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Poland (PL)', function (): void {
    it('parses simple street with number', function (): void {
        $result = $this->service->split('Marszałkowska 49', 'PL');

        expect($result->street)->toBe('Marszałkowska')
            ->and($result->buildingNumber)->toBe('49');
    });

    it('parses street with prefix ul.', function (): void {
        $result = $this->service->split('ul. Marszałkowska 49', 'PL');

        expect($result->street)->toBe('ul. Marszałkowska')
            ->and($result->buildingNumber)->toBe('49');
    });

    it('parses street with prefix Ulica', function (): void {
        $result = $this->service->split('Ulica Marszałkowska 10', 'PL');

        expect($result->street)->toBe('Ulica Marszałkowska')
            ->and($result->buildingNumber)->toBe('10');
    });

    it('parses aleja prefix', function (): void {
        $result = $this->service->split('Aleja Wojska Polskiego 49a', 'PL');

        expect($result->street)->toBe('Aleja Wojska Polskiego')
            ->and($result->buildingNumber)->toBe('49a');
    });

    it('parses al. prefix', function (): void {
        $result = $this->service->split('al. Wojska Polskiego 49a', 'PL');

        expect($result->street)->toBe('al. Wojska Polskiego')
            ->and($result->buildingNumber)->toBe('49a');
    });

    it('parses abbreviated street with apartment using m.', function (): void {
        $result = $this->service->split('al. Woj. Polskiego 49a m.15', 'PL');

        expect($result->street)->toBe('al. Woj. Polskiego')
            ->and($result->buildingNumber)->toBe('49a')
            ->and($result->apartmentNumber)->toBe('15');
    });

    it('parses apartment with m (no dot)', function (): void {
        $result = $this->service->split('al. Wojska Polskiego 49a m 15', 'PL');

        expect($result->street)->toBe('al. Wojska Polskiego')
            ->and($result->buildingNumber)->toBe('49a')
            ->and($result->apartmentNumber)->toBe('15');
    });

    it('parses apartment with mieszkania', function (): void {
        $result = $this->service->split('ul. Długa 5 mieszkania 12', 'PL');

        expect($result->street)->toBe('ul. Długa')
            ->and($result->buildingNumber)->toBe('5')
            ->and($result->apartmentNumber)->toBe('12');
    });

    it('parses apartment with lok.', function (): void {
        $result = $this->service->split('ul. Długa 5 lok. 12', 'PL');

        expect($result->street)->toBe('ul. Długa')
            ->and($result->buildingNumber)->toBe('5')
            ->and($result->apartmentNumber)->toBe('12');
    });

    it('parses apartment with slash', function (): void {
        $result = $this->service->split('os. Tysiąclecia 15/3', 'PL');

        expect($result->street)->toBe('os. Tysiąclecia')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses street name starting with number - 3 Maja', function (): void {
        $result = $this->service->split('3 Maja 49', 'PL');

        expect($result->street)->toBe('3 Maja')
            ->and($result->buildingNumber)->toBe('49');
    });

    it('parses street name starting with number with prefix - ul. 3 Maja', function (): void {
        $result = $this->service->split('ul. 3 Maja 49', 'PL');

        expect($result->street)->toBe('ul. 3 Maja')
            ->and($result->buildingNumber)->toBe('49');
    });

    it('parses 11 Listopada street', function (): void {
        $result = $this->service->split('11 Listopada 49 m. 3', 'PL');

        expect($result->street)->toBe('11 Listopada')
            ->and($result->buildingNumber)->toBe('49')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses street with Roman numeral - Karola III Wielkiego', function (): void {
        $result = $this->service->split('Karola III Wielkiego 12', 'PL');

        expect($result->street)->toBe('Karola III Wielkiego')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Rondo Mogilskie', function (): void {
        $result = $this->service->split('Rondo Mogilskie 1', 'PL');

        expect($result->street)->toBe('Rondo Mogilskie')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses pl. Wolności', function (): void {
        $result = $this->service->split('pl. Wolności 5', 'PL');

        expect($result->street)->toBe('pl. Wolności')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses osiedle', function (): void {
        $result = $this->service->split('Osiedle Bohaterów Września 15/3', 'PL');

        expect($result->street)->toBe('Osiedle Bohaterów Września')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses Bulwar prefix', function (): void {
        $result = $this->service->split('Bulwar Czerwiński 7', 'PL');

        expect($result->street)->toBe('Bulwar Czerwiński')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses building range', function (): void {
        $result = $this->service->split('ul. Długa 12-14', 'PL');

        expect($result->street)->toBe('ul. Długa')
            ->and($result->buildingNumber)->toBe('12-14');
    });

    it('parses 1000-lecia', function (): void {
        $result = $this->service->split('1000-lecia 15', 'PL');

        expect($result->street)->toBe('1000-lecia')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('handles extra whitespace', function (): void {
        $result = $this->service->split('  ul.  Marszałkowska   49  ', 'PL');

        expect($result->street)->toBe('ul. Marszałkowska')
            ->and($result->buildingNumber)->toBe('49');
    });

    // --- Zielona Góra: hyphenated village-prefix street names ---

    it('parses hyphenated village-prefix street — Drzonków-Sowia', function (): void {
        $result = $this->service->split('Drzonków-Sowia 15', 'PL');

        expect($result->street)->toBe('Drzonków-Sowia')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses hyphenated street with slash apartment — Łężyca-Dolna', function (): void {
        $result = $this->service->split('Łężyca-Dolna 3/2', 'PL');

        expect($result->street)->toBe('Łężyca-Dolna')
            ->and($result->buildingNumber)->toBe('3')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses hyphenated street with letter suffix — Kiełpin-Lwa', function (): void {
        $result = $this->service->split('Kiełpin-Lwa 8A', 'PL');

        expect($result->street)->toBe('Kiełpin-Lwa')
            ->and($result->buildingNumber)->toBe('8A');
    });

    it('parses multi-word village prefix — Nowy Kisielin-Brzozowa', function (): void {
        $result = $this->service->split('Nowy Kisielin-Brzozowa 7', 'PL');

        expect($result->street)->toBe('Nowy Kisielin-Brzozowa')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses multi-word village prefix with apartment — Stary Kisielin-Akacjowa', function (): void {
        $result = $this->service->split('Stary Kisielin-Akacjowa 12 m. 5', 'PL');

        expect($result->street)->toBe('Stary Kisielin-Akacjowa')
            ->and($result->buildingNumber)->toBe('12')
            ->and($result->apartmentNumber)->toBe('5');
    });

    it('parses hyphenated street with multi-word suffix — Ochla-Dębowa Polana', function (): void {
        $result = $this->service->split('Ochla-Dębowa Polana 1', 'PL');

        expect($result->street)->toBe('Ochla-Dębowa Polana')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses hyphenated village with date street — Ochla-3 Maja', function (): void {
        $result = $this->service->split('Ochla-3 Maja 5', 'PL');

        expect($result->street)->toBe('Ochla-3 Maja')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses hyphenated street with building range — Drzonków-Bukowa', function (): void {
        $result = $this->service->split('Drzonków-Bukowa 12-14', 'PL');

        expect($result->street)->toBe('Drzonków-Bukowa')
            ->and($result->buildingNumber)->toBe('12-14');
    });

    it('parses hyphenated street with two-word suffix — Drzonków-Sosnowy Zakątek', function (): void {
        $result = $this->service->split('Drzonków-Sosnowy Zakątek 3', 'PL');

        expect($result->street)->toBe('Drzonków-Sosnowy Zakątek')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses hyphenated street with initial — Drzonków-E. Romera', function (): void {
        $result = $this->service->split('Drzonków-E. Romera 5A m.2', 'PL');

        expect($result->street)->toBe('Drzonków-E. Romera')
            ->and($result->buildingNumber)->toBe('5A')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses ul. prefix with hyphenated street — Krępa-Leśna', function (): void {
        $result = $this->service->split('ul. Krępa-Leśna 4', 'PL');

        expect($result->street)->toBe('ul. Krępa-Leśna')
            ->and($result->buildingNumber)->toBe('4');
    });

    it('parses hyphenated street with lok. apartment — Przylep-Malinowa', function (): void {
        $result = $this->service->split('Przylep-Malinowa 5A lok. 2', 'PL');

        expect($result->street)->toBe('Przylep-Malinowa')
            ->and($result->buildingNumber)->toBe('5A')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses village prefix with date street and apartment — Racula-11 Listopada', function (): void {
        $result = $this->service->split('Racula-11 Listopada 5 m. 3', 'PL');

        expect($result->street)->toBe('Racula-11 Listopada')
            ->and($result->buildingNumber)->toBe('5')
            ->and($result->apartmentNumber)->toBe('3');
    });

    // --- Zielona Góra: multi-word & special street names ---

    it('parses Aleja Konstytucji 3 Maja with apartment', function (): void {
        $result = $this->service->split('Aleja Konstytucji 3 Maja 12/4', 'PL');

        expect($result->street)->toBe('Aleja Konstytucji 3 Maja')
            ->and($result->buildingNumber)->toBe('12')
            ->and($result->apartmentNumber)->toBe('4');
    });

    it('parses Szosa prefix — Szosa Kisielińska', function (): void {
        $result = $this->service->split('Szosa Kisielińska 22', 'PL');

        expect($result->street)->toBe('Szosa Kisielińska')
            ->and($result->buildingNumber)->toBe('22');
    });

    it('parses Plac with multi-word name — Plac Powstańców Wielkopolskich', function (): void {
        $result = $this->service->split('Plac Powstańców Wielkopolskich 5', 'PL');

        expect($result->street)->toBe('Plac Powstańców Wielkopolskich')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Bohaterów Westerplatte with apartment', function (): void {
        $result = $this->service->split('Bohaterów Westerplatte 8/2', 'PL');

        expect($result->street)->toBe('Bohaterów Westerplatte')
            ->and($result->buildingNumber)->toBe('8')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses street name with conjunction — Bolka i Lolka', function (): void {
        $result = $this->service->split('Bolka i Lolka 3', 'PL');

        expect($result->street)->toBe('Bolka i Lolka')
            ->and($result->buildingNumber)->toBe('3');
    });
});
