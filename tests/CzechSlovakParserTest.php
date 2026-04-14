<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Czech Republic (CZ)', function (): void {
    it('parses simple address', function (): void {
        $result = $this->service->split('Václavské náměstí 12', 'CZ');

        expect($result->street)->toBe('Václavské náměstí')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses dual numbering system', function (): void {
        $result = $this->service->split('Karlova 1/15', 'CZ');

        expect($result->street)->toBe('Karlova')
            ->and($result->buildingNumber)->toBe('1')
            ->and($result->apartmentNumber)->toBe('15');
    });

    it('parses with nám. prefix', function (): void {
        $result = $this->service->split('nám. Míru 820/9', 'CZ');

        expect($result->street)->toBe('nám. Míru')
            ->and($result->buildingNumber)->toBe('820')
            ->and($result->apartmentNumber)->toBe('9');
    });

    // --- Street prefix variants ---

    it('parses tř. prefix with dual numbering', function (): void {
        $result = $this->service->split('tř. Kpt. Jaroše 1922/3', 'CZ');

        expect($result->street)->toBe('tř. Kpt. Jaroše')
            ->and($result->buildingNumber)->toBe('1922')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses ul. prefix', function (): void {
        $result = $this->service->split('ul. Družstevní 7', 'CZ');

        expect($result->street)->toBe('ul. Družstevní')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses nábř. prefix', function (): void {
        $result = $this->service->split('nábř. Edvarda Beneše 12', 'CZ');

        expect($result->street)->toBe('nábř. Edvarda Beneše')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses full náměstí prefix with dual numbering', function (): void {
        $result = $this->service->split('náměstí Republiky 1090/5', 'CZ');

        expect($result->street)->toBe('náměstí Republiky')
            ->and($result->buildingNumber)->toBe('1090')
            ->and($result->apartmentNumber)->toBe('5');
    });

    it('parses full třída prefix', function (): void {
        $result = $this->service->split('třída T. G. Masaryka 18', 'CZ');

        expect($result->street)->toBe('třída T. G. Masaryka')
            ->and($result->buildingNumber)->toBe('18');
    });

    it('parses Sady prefix', function (): void {
        $result = $this->service->split('Sady Pětatřicátníků 33', 'CZ');

        expect($result->street)->toBe('Sady Pětatřicátníků')
            ->and($result->buildingNumber)->toBe('33');
    });

    // --- Czech street patterns ---

    it('parses street with Na preposition', function (): void {
        $result = $this->service->split('Na Poříčí 42', 'CZ');

        expect($result->street)->toBe('Na Poříčí')
            ->and($result->buildingNumber)->toBe('42');
    });

    it('parses dual number with letter suffix', function (): void {
        $result = $this->service->split('Dlouhá 730/35b', 'CZ');

        expect($result->street)->toBe('Dlouhá')
            ->and($result->buildingNumber)->toBe('730')
            ->and($result->apartmentNumber)->toBe('35b');
    });

    it('parses simple Národní', function (): void {
        $result = $this->service->split('Národní 37', 'CZ');

        expect($result->street)->toBe('Národní')
            ->and($result->buildingNumber)->toBe('37');
    });

    it('parses Pod Lipami with dual numbering', function (): void {
        $result = $this->service->split('Pod Lipami 339/10', 'CZ');

        expect($result->street)->toBe('Pod Lipami')
            ->and($result->buildingNumber)->toBe('339')
            ->and($result->apartmentNumber)->toBe('10');
    });

    it('parses full ulice prefix', function (): void {
        $result = $this->service->split('ulice Sportovní 25', 'CZ');

        expect($result->street)->toBe('ulice Sportovní')
            ->and($result->buildingNumber)->toBe('25');
    });

    it('parses nábřeží full prefix with dual numbering', function (): void {
        $result = $this->service->split('nábřeží Ludvíka Svobody 1222/12', 'CZ');

        expect($result->street)->toBe('nábřeží Ludvíka Svobody')
            ->and($result->buildingNumber)->toBe('1222')
            ->and($result->apartmentNumber)->toBe('12');
    });
});

describe('Slovakia (SK)', function (): void {
    it('parses Slovak address', function (): void {
        $result = $this->service->split('Hlavná 12', 'SK');

        expect($result->street)->toBe('Hlavná')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses with letter suffix', function (): void {
        $result = $this->service->split('Obchodná 5a', 'SK');

        expect($result->street)->toBe('Obchodná')
            ->and($result->buildingNumber)->toBe('5a');
    });

    it('parses Námestie slobody', function (): void {
        $result = $this->service->split('Námestie slobody 12', 'SK');

        expect($result->street)->toBe('Námestie slobody')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Slovak dual numbering', function (): void {
        $result = $this->service->split('Štúrova 15/3', 'SK');

        expect($result->street)->toBe('Štúrova')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses Štefánikova', function (): void {
        $result = $this->service->split('Štefánikova 100', 'SK');

        expect($result->street)->toBe('Štefánikova')
            ->and($result->buildingNumber)->toBe('100');
    });
});
