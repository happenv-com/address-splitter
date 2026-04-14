<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Italy (IT)', function (): void {
    it('parses Via Roma 15', function (): void {
        $result = $this->service->split('Via Roma 15', 'IT');

        expect($result->street)->toBe('Via Roma')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Via Roma, 15', function (): void {
        $result = $this->service->split('Via Roma, 15', 'IT');

        expect($result->street)->toBe('Via Roma')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Via with slash apartment', function (): void {
        $result = $this->service->split('Via Roma 15/A', 'IT');

        expect($result->street)->toBe('Via Roma')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('A');
    });

    it('parses Piazza Duomo', function (): void {
        $result = $this->service->split('Piazza Duomo 1', 'IT');

        expect($result->street)->toBe('Piazza Duomo')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Corso with interno', function (): void {
        $result = $this->service->split('Corso Vittorio Emanuele 23, int. 5', 'IT');

        expect($result->street)->toBe('Corso Vittorio Emanuele')
            ->and($result->buildingNumber)->toBe('23')
            ->and($result->apartmentNumber)->toBe('5');
    });

    // --- Italian street type prefixes ---

    it('parses Viale prefix — Viale dei Mille', function (): void {
        $result = $this->service->split('Viale dei Mille 15', 'IT');

        expect($result->street)->toBe('Viale dei Mille')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses V.le abbreviation — V.le Europa', function (): void {
        $result = $this->service->split('V.le Europa 8', 'IT');

        expect($result->street)->toBe('V.le Europa')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses P.za abbreviation — P.za Navona', function (): void {
        $result = $this->service->split('P.za Navona 10', 'IT');

        expect($result->street)->toBe('P.za Navona')
            ->and($result->buildingNumber)->toBe('10');
    });

    it('parses P.zza abbreviation — P.zza Venezia', function (): void {
        $result = $this->service->split('P.zza Venezia 5', 'IT');

        expect($result->street)->toBe('P.zza Venezia')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Largo prefix — Largo Augusto', function (): void {
        $result = $this->service->split('Largo Augusto 3', 'IT');

        expect($result->street)->toBe('Largo Augusto')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses Vicolo prefix — Vicolo Stretto', function (): void {
        $result = $this->service->split('Vicolo Stretto 2', 'IT');

        expect($result->street)->toBe('Vicolo Stretto')
            ->and($result->buildingNumber)->toBe('2');
    });

    it('parses Contrada prefix — Contrada Santa Maria', function (): void {
        $result = $this->service->split('Contrada Santa Maria 5', 'IT');

        expect($result->street)->toBe('Contrada Santa Maria')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Borgo prefix — Borgo Pio', function (): void {
        $result = $this->service->split('Borgo Pio 12', 'IT');

        expect($result->street)->toBe('Borgo Pio')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Lungomare prefix — Lungomare Colombo', function (): void {
        $result = $this->service->split('Lungomare Colombo 18', 'IT');

        expect($result->street)->toBe('Lungomare Colombo')
            ->and($result->buildingNumber)->toBe('18');
    });

    it('parses Strada prefix — Strada Provinciale', function (): void {
        $result = $this->service->split('Strada Provinciale 42', 'IT');

        expect($result->street)->toBe('Strada Provinciale')
            ->and($result->buildingNumber)->toBe('42');
    });

    it('parses Salita prefix — Salita di San Gregorio', function (): void {
        $result = $this->service->split('Salita di San Gregorio 7', 'IT');

        expect($result->street)->toBe('Salita di San Gregorio')
            ->and($result->buildingNumber)->toBe('7');
    });

    // --- Venetian address types ---

    it('parses Calle (Venetian) — Calle Larga', function (): void {
        $result = $this->service->split('Calle Larga 22', 'IT');

        expect($result->street)->toBe('Calle Larga')
            ->and($result->buildingNumber)->toBe('22');
    });

    it('parses Fondamenta (Venetian) — Fondamenta Nuove', function (): void {
        $result = $this->service->split('Fondamenta Nuove 5', 'IT');

        expect($result->street)->toBe('Fondamenta Nuove')
            ->and($result->buildingNumber)->toBe('5');
    });

    // --- Date and Roman numeral streets ---

    it('parses date street — Via 20 Settembre', function (): void {
        $result = $this->service->split('Via 20 Settembre 12', 'IT');

        expect($result->street)->toBe('Via 20 Settembre')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Roman numeral street — Via IV Novembre', function (): void {
        $result = $this->service->split('Via IV Novembre 8', 'IT');

        expect($result->street)->toBe('Via IV Novembre')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses Roman numeral date — Via XXIV Maggio', function (): void {
        $result = $this->service->split('Via XXIV Maggio 6', 'IT');

        expect($result->street)->toBe('Via XXIV Maggio')
            ->and($result->buildingNumber)->toBe('6');
    });

    // --- Apartment separator variants ---

    it('parses scala apartment indicator — Via Garibaldi, sc. 3', function (): void {
        $result = $this->service->split('Via Garibaldi 10, sc. 3', 'IT');

        expect($result->street)->toBe('Via Garibaldi')
            ->and($result->buildingNumber)->toBe('10')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses piano apartment indicator — Via Dante, p. 2', function (): void {
        $result = $this->service->split('Via Dante 7, p. 2', 'IT');

        expect($result->street)->toBe('Via Dante')
            ->and($result->buildingNumber)->toBe('7')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses full "interno" — Via Mazzini interno 6', function (): void {
        $result = $this->service->split('Via Mazzini 4 interno 6', 'IT');

        expect($result->street)->toBe('Via Mazzini')
            ->and($result->buildingNumber)->toBe('4')
            ->and($result->apartmentNumber)->toBe('6');
    });

    // --- Multi-word and "del/della/dei" streets ---

    it('parses Via del Corso', function (): void {
        $result = $this->service->split('Via del Corso 10', 'IT');

        expect($result->street)->toBe('Via del Corso')
            ->and($result->buildingNumber)->toBe('10');
    });

    it('parses Via dei Fori Imperiali', function (): void {
        $result = $this->service->split('Via dei Fori Imperiali 1', 'IT');

        expect($result->street)->toBe('Via dei Fori Imperiali')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Via della Conciliazione', function (): void {
        $result = $this->service->split('Via della Conciliazione 44', 'IT');

        expect($result->street)->toBe('Via della Conciliazione')
            ->and($result->buildingNumber)->toBe('44');
    });

    it('parses multi-word — Via Fratelli Cervi', function (): void {
        $result = $this->service->split('Via Fratelli Cervi 22', 'IT');

        expect($result->street)->toBe('Via Fratelli Cervi')
            ->and($result->buildingNumber)->toBe('22');
    });

    // --- Comma + esponente (slash) patterns from Poste Italiane spec ---

    it('parses comma + number + slash esponente — Via Etnea, 234/B', function (): void {
        $result = $this->service->split('Via Etnea, 234/B', 'IT');

        expect($result->street)->toBe('Via Etnea')
            ->and($result->buildingNumber)->toBe('234')
            ->and($result->apartmentNumber)->toBe('B');
    });

    it('parses Corso abbreviation — C.so Buenos Aires', function (): void {
        $result = $this->service->split('C.so Buenos Aires 15', 'IT');

        expect($result->street)->toBe('C.so Buenos Aires')
            ->and($result->buildingNumber)->toBe('15');
    });
});
