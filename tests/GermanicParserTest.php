<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Germany (DE)', function (): void {
    it('parses Hauptstraße 12', function (): void {
        $result = $this->service->split('Hauptstraße 12', 'DE');

        expect($result->street)->toBe('Hauptstraße')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses abbreviated street', function (): void {
        $result = $this->service->split('Müllerstr. 5a', 'DE');

        expect($result->street)->toBe('Müllerstr.')
            ->and($result->buildingNumber)->toBe('5a');
    });

    it('parses Am Graben 7', function (): void {
        $result = $this->service->split('Am Graben 7', 'DE');

        expect($result->street)->toBe('Am Graben')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses building with slash apartment', function (): void {
        $result = $this->service->split('Bahnhofstr. 12/3', 'DE');

        expect($result->street)->toBe('Bahnhofstr.')
            ->and($result->buildingNumber)->toBe('12')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses hyphenated street name', function (): void {
        $result = $this->service->split('Karl-Marx-Allee 78', 'DE');

        expect($result->street)->toBe('Karl-Marx-Allee')
            ->and($result->buildingNumber)->toBe('78');
    });

    it('parses Unter den Linden 1', function (): void {
        $result = $this->service->split('Unter den Linden 1', 'DE');

        expect($result->street)->toBe('Unter den Linden')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Whg apartment indicator', function (): void {
        $result = $this->service->split('Dorfstr. 10 Whg. 2', 'DE');

        expect($result->street)->toBe('Dorfstr.')
            ->and($result->buildingNumber)->toBe('10')
            ->and($result->apartmentNumber)->toBe('2');
    });

    // --- German address patterns from Wikipedia (Hausnummer / Postanschrift) ---

    it('parses uppercase letter suffix — Friedrichstraße 12A', function (): void {
        $result = $this->service->split('Friedrichstraße 12A', 'DE');

        expect($result->street)->toBe('Friedrichstraße')
            ->and($result->buildingNumber)->toBe('12A');
    });

    it('parses long compound street — Kurfürstendamm 237', function (): void {
        $result = $this->service->split('Kurfürstendamm 237', 'DE');

        expect($result->street)->toBe('Kurfürstendamm')
            ->and($result->buildingNumber)->toBe('237');
    });

    it('parses multi-word street with "An der" — An der Schillingbrücke', function (): void {
        $result = $this->service->split('An der Schillingbrücke 4', 'DE');

        expect($result->street)->toBe('An der Schillingbrücke')
            ->and($result->buildingNumber)->toBe('4');
    });

    it('parses multi-word street with "Im" — Im Neuenheimer Feld', function (): void {
        $result = $this->service->split('Im Neuenheimer Feld 327', 'DE');

        expect($result->street)->toBe('Im Neuenheimer Feld')
            ->and($result->buildingNumber)->toBe('327');
    });

    it('parses multi-word street with "Auf dem" — Auf dem Mühlenberg', function (): void {
        $result = $this->service->split('Auf dem Mühlenberg 8', 'DE');

        expect($result->street)->toBe('Auf dem Mühlenberg')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses four-digit house number — Venloer Straße 1451 (Köln)', function (): void {
        $result = $this->service->split('Venloer Straße 1451', 'DE');

        expect($result->street)->toBe('Venloer Straße')
            ->and($result->buildingNumber)->toBe('1451');
    });

    it('parses Wohnung apartment indicator', function (): void {
        $result = $this->service->split('Berliner Str. 5 Wohnung 12', 'DE');

        expect($result->street)->toBe('Berliner Str.')
            ->and($result->buildingNumber)->toBe('5')
            ->and($result->apartmentNumber)->toBe('12');
    });

    it('parses Nr. apartment indicator', function (): void {
        $result = $this->service->split('Schillerstraße 8 Nr. 3', 'DE');

        expect($result->street)->toBe('Schillerstraße')
            ->and($result->buildingNumber)->toBe('8')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses App. apartment indicator', function (): void {
        $result = $this->service->split('Goethestraße 15 App. 4', 'DE');

        expect($result->street)->toBe('Goethestraße')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('4');
    });

    it('parses building range — Berliner Str. 38-40', function (): void {
        $result = $this->service->split('Berliner Str. 38-40', 'DE');

        expect($result->street)->toBe('Berliner Str.')
            ->and($result->buildingNumber)->toBe('38-40');
    });

    it('parses building range with apartment — Berliner Str. 38-40 Whg. 2', function (): void {
        $result = $this->service->split('Berliner Str. 38-40 Whg. 2', 'DE');

        expect($result->street)->toBe('Berliner Str.')
            ->and($result->buildingNumber)->toBe('38-40')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses Platz ending — Alexanderplatz', function (): void {
        $result = $this->service->split('Alexanderplatz 1', 'DE');

        expect($result->street)->toBe('Alexanderplatz')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Ring ending — Sachsenring', function (): void {
        $result = $this->service->split('Sachsenring 5', 'DE');

        expect($result->street)->toBe('Sachsenring')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses multi-part hyphenated name — Von-der-Tann-Straße', function (): void {
        $result = $this->service->split('Von-der-Tann-Straße 5', 'DE');

        expect($result->street)->toBe('Von-der-Tann-Straße')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses street with "Große" — Große Hamburger Straße', function (): void {
        $result = $this->service->split('Große Hamburger Straße 17', 'DE');

        expect($result->street)->toBe('Große Hamburger Straße')
            ->and($result->buildingNumber)->toBe('17');
    });

    it('parses street name containing a number — Straße des 17. Juni', function (): void {
        $result = $this->service->split('Straße des 17. Juni 135', 'DE');

        expect($result->street)->toBe('Straße des 17. Juni')
            ->and($result->buildingNumber)->toBe('135');
    });
});

describe('Austria (AT)', function (): void {
    it('parses Schönbrunner Str. with Top', function (): void {
        $result = $this->service->split('Schönbrunner Str. 23 Top 4', 'AT');

        expect($result->street)->toBe('Schönbrunner Str.')
            ->and($result->buildingNumber)->toBe('23')
            ->and($result->apartmentNumber)->toBe('4');
    });

    it('uses DE parser via AT country code', function (): void {
        $result = $this->service->split('Wienzeile 8', 'AT');

        expect($result->street)->toBe('Wienzeile')
            ->and($result->buildingNumber)->toBe('8');
    });

    // --- Austrian-specific address patterns ---

    it('parses Stiege apartment indicator — Seestraße 1a Stiege 2', function (): void {
        $result = $this->service->split('Seestraße 1a Stiege 2', 'AT');

        expect($result->street)->toBe('Seestraße')
            ->and($result->buildingNumber)->toBe('1a')
            ->and($result->apartmentNumber)->toBe('2');
    });

    it('parses building range — Mariahilfer Straße 38-40', function (): void {
        $result = $this->service->split('Mariahilfer Straße 38-40', 'AT');

        expect($result->street)->toBe('Mariahilfer Straße')
            ->and($result->buildingNumber)->toBe('38-40');
    });

    it('parses Gasse with range and Top — Ungargasse 27-29 Top 5', function (): void {
        $result = $this->service->split('Ungargasse 27-29 Top 5', 'AT');

        expect($result->street)->toBe('Ungargasse')
            ->and($result->buildingNumber)->toBe('27-29')
            ->and($result->apartmentNumber)->toBe('5');
    });
});

describe('Switzerland (CH)', function (): void {
    it('parses Swiss address', function (): void {
        $result = $this->service->split('Bahnhofstrasse 25', 'CH');

        expect($result->street)->toBe('Bahnhofstrasse')
            ->and($result->buildingNumber)->toBe('25');
    });

    // --- Swiss-specific address patterns ---

    it('parses long Swiss compound name — Rämistrasse 101', function (): void {
        $result = $this->service->split('Rämistrasse 101', 'CH');

        expect($result->street)->toBe('Rämistrasse')
            ->and($result->buildingNumber)->toBe('101');
    });

    it('parses Swiss address with slash apartment', function (): void {
        $result = $this->service->split('Langstrasse 150/2', 'CH');

        expect($result->street)->toBe('Langstrasse')
            ->and($result->buildingNumber)->toBe('150')
            ->and($result->apartmentNumber)->toBe('2');
    });
});
