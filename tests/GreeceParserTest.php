<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Greece (GR)', function (): void {
    it('parses Greek address', function (): void {
        $result = $this->service->split('Ερμού 15', 'GR');

        expect($result->street)->toBe('Ερμού')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with Οδός prefix', function (): void {
        $result = $this->service->split('Οδός Ερμού 15', 'GR');

        expect($result->street)->toBe('Οδός Ερμού')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with floor info', function (): void {
        $result = $this->service->split('Ερμού 15, 3ος όροφος', 'GR');

        expect($result->street)->toBe('Ερμού')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3ος όροφος');
    });

    it('parses Λεωφόρος (avenue) type', function (): void {
        $result = $this->service->split('Λεωφόρος Αλεξάνδρας 120', 'GR');

        expect($result->street)->toBe('Λεωφόρος Αλεξάνδρας')
            ->and($result->buildingNumber)->toBe('120');
    });

    it('parses Πλατεία (square) type', function (): void {
        $result = $this->service->split('Πλατεία Συντάγματος 1', 'GR');

        expect($result->street)->toBe('Πλατεία Συντάγματος')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Latin transliteration Odos', function (): void {
        $result = $this->service->split('Odos Ermou 15', 'GR');

        expect($result->street)->toBe('Odos Ermou')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Latin Leoforos type', function (): void {
        $result = $this->service->split('Leoforos Alexandras 120', 'GR');

        expect($result->street)->toBe('Leoforos Alexandras')
            ->and($result->buildingNumber)->toBe('120');
    });

    it('parses with διαμ. (apartment)', function (): void {
        $result = $this->service->split('Ερμού 40, διαμ. 5', 'GR');

        expect($result->street)->toBe('Ερμού')
            ->and($result->buildingNumber)->toBe('40')
            ->and($result->apartmentNumber)->toBe('διαμ. 5');
    });

    it('parses multi-word street Βασιλίσσης Σοφίας', function (): void {
        $result = $this->service->split('Βασιλίσσης Σοφίας 7', 'GR');

        expect($result->street)->toBe('Βασιλίσσης Σοφίας')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses Σταδίου simple', function (): void {
        $result = $this->service->split('Σταδίου 33', 'GR');

        expect($result->street)->toBe('Σταδίου')
            ->and($result->buildingNumber)->toBe('33');
    });
});
