<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Portugal (PT)', function (): void {
    it('parses Rua Augusta 15', function (): void {
        $result = $this->service->split('Rua Augusta 15', 'PT');

        expect($result->street)->toBe('Rua Augusta')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with floor info', function (): void {
        $result = $this->service->split('Rua Augusta 15, 3º Esq.', 'PT');

        expect($result->street)->toBe('Rua Augusta')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3º Esq.');
    });

    it('parses Avenida', function (): void {
        $result = $this->service->split('Av. da Liberdade 120', 'PT');

        expect($result->street)->toBe('Av. da Liberdade')
            ->and($result->buildingNumber)->toBe('120');
    });

    it('parses Praça with R/C', function (): void {
        $result = $this->service->split('Praça do Comércio 1, R/C', 'PT');

        expect($result->street)->toBe('Praça do Comércio')
            ->and($result->buildingNumber)->toBe('1')
            ->and($result->apartmentNumber)->toBe('R/C');
    });

    // --- Street type variants ---

    it('parses Travessa type', function (): void {
        $result = $this->service->split('Travessa do Carmo 8', 'PT');

        expect($result->street)->toBe('Travessa do Carmo')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses Largo type', function (): void {
        $result = $this->service->split('Largo do Chiado 16', 'PT');

        expect($result->street)->toBe('Largo do Chiado')
            ->and($result->buildingNumber)->toBe('16');
    });

    it('parses Alameda type', function (): void {
        $result = $this->service->split('Alameda D. Afonso Henriques 44', 'PT');

        expect($result->street)->toBe('Alameda D. Afonso Henriques')
            ->and($result->buildingNumber)->toBe('44');
    });

    it('parses Beco type', function (): void {
        $result = $this->service->split('Beco do Espírito Santo 3', 'PT');

        expect($result->street)->toBe('Beco do Espírito Santo')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses Calçada type', function (): void {
        $result = $this->service->split('Calçada do Combro 58', 'PT');

        expect($result->street)->toBe('Calçada do Combro')
            ->and($result->buildingNumber)->toBe('58');
    });

    it('parses Estrada type', function (): void {
        $result = $this->service->split('Estrada de Benfica 200', 'PT');

        expect($result->street)->toBe('Estrada de Benfica')
            ->and($result->buildingNumber)->toBe('200');
    });

    it('parses Caminho type', function (): void {
        $result = $this->service->split('Caminho do Monte 5', 'PT');

        expect($result->street)->toBe('Caminho do Monte')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Trav. abbreviation', function (): void {
        $result = $this->service->split('Trav. do Fala-Só 12', 'PT');

        expect($result->street)->toBe('Trav. do Fala-Só')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Pça. abbreviation', function (): void {
        $result = $this->service->split('Pça. dos Restauradores 58', 'PT');

        expect($result->street)->toBe('Pça. dos Restauradores')
            ->and($result->buildingNumber)->toBe('58');
    });

    // --- Apartment / floor variants ---

    it('parses Dto. (right side)', function (): void {
        $result = $this->service->split('Rua do Ouro 240, 2º Dto.', 'PT');

        expect($result->street)->toBe('Rua do Ouro')
            ->and($result->buildingNumber)->toBe('240')
            ->and($result->apartmentNumber)->toBe('2º Dto.');
    });

    it('parses R/C Esq. (ground floor left)', function (): void {
        $result = $this->service->split('Rua Augusta 100, R/C Esq.', 'PT');

        expect($result->street)->toBe('Rua Augusta')
            ->and($result->buildingNumber)->toBe('100')
            ->and($result->apartmentNumber)->toBe('R/C Esq.');
    });

    it('parses floor only: 1º', function (): void {
        $result = $this->service->split('Av. da República 45, 1º', 'PT');

        expect($result->street)->toBe('Av. da República')
            ->and($result->buildingNumber)->toBe('45')
            ->and($result->apartmentNumber)->toBe('1º');
    });

    it('parses Lote separator', function (): void {
        $result = $this->service->split('Rua da Prata 80 Lote B', 'PT');

        expect($result->street)->toBe('Rua da Prata')
            ->and($result->buildingNumber)->toBe('80')
            ->and($result->apartmentNumber)->toBe('B');
    });
});
