<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('Spain (ES)', function (): void {
    it('parses Calle Mayor 15', function (): void {
        $result = $this->service->split('Calle Mayor 15', 'ES');

        expect($result->street)->toBe('Calle Mayor')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses C/ abbreviation', function (): void {
        $result = $this->service->split('C/ Mayor 15', 'ES');

        expect($result->street)->toBe('C/ Mayor')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses with floor and door', function (): void {
        $result = $this->service->split('Calle Mayor 15, 3º 2ª', 'ES');

        expect($result->street)->toBe('Calle Mayor')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3º 2ª');
    });

    it('parses Avenida', function (): void {
        $result = $this->service->split('Avda. de la Constitución 12', 'ES');

        expect($result->street)->toBe('Avda. de la Constitución')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Plaza sin número', function (): void {
        $result = $this->service->split('Plaza de España s/n', 'ES');

        expect($result->street)->toBe('Plaza de España')
            ->and($result->buildingNumber)->toBe('s/n');
    });

    // --- Street type prefix coverage ---

    it('parses Paseo de la Castellana 45', function (): void {
        $result = $this->service->split('Paseo de la Castellana 45', 'ES');

        expect($result->street)->toBe('Paseo de la Castellana')
            ->and($result->buildingNumber)->toBe('45');
    });

    it('parses Pº abbreviation', function (): void {
        $result = $this->service->split('Pº del Prado 28', 'ES');

        expect($result->street)->toBe('Pº del Prado')
            ->and($result->buildingNumber)->toBe('28');
    });

    it('parses Camino de las Cruces 7', function (): void {
        $result = $this->service->split('Camino de las Cruces 7', 'ES');

        expect($result->street)->toBe('Camino de las Cruces')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses Ctra. abbreviation', function (): void {
        $result = $this->service->split('Ctra. de Colmenar 15', 'ES');

        expect($result->street)->toBe('Ctra. de Colmenar')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Ronda de Valencia 7', function (): void {
        $result = $this->service->split('Ronda de Valencia 7', 'ES');

        expect($result->street)->toBe('Ronda de Valencia')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses Travesía de San Mateo 4', function (): void {
        $result = $this->service->split('Travesía de San Mateo 4', 'ES');

        expect($result->street)->toBe('Travesía de San Mateo')
            ->and($result->buildingNumber)->toBe('4');
    });

    it('parses Glorieta de Bilbao 1', function (): void {
        $result = $this->service->split('Glorieta de Bilbao 1', 'ES');

        expect($result->street)->toBe('Glorieta de Bilbao')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Bulevar de la Naturaleza 12', function (): void {
        $result = $this->service->split('Bulevar de la Naturaleza 12', 'ES');

        expect($result->street)->toBe('Bulevar de la Naturaleza')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Costanilla de los Ángeles 8', function (): void {
        $result = $this->service->split('Costanilla de los Ángeles 8', 'ES');

        expect($result->street)->toBe('Costanilla de los Ángeles')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses Callejón del Gato 3', function (): void {
        $result = $this->service->split('Callejón del Gato 3', 'ES');

        expect($result->street)->toBe('Callejón del Gato')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses Particular de Indautxu 5', function (): void {
        $result = $this->service->split('Particular de Indautxu 5', 'ES');

        expect($result->street)->toBe('Particular de Indautxu')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Cl. abbreviation', function (): void {
        $result = $this->service->split('Cl. Real 10', 'ES');

        expect($result->street)->toBe('Cl. Real')
            ->and($result->buildingNumber)->toBe('10');
    });

    // --- Additional abbreviation variants ---

    it('parses Av. abbreviation', function (): void {
        $result = $this->service->split('Av. Diagonal 405', 'ES');

        expect($result->street)->toBe('Av. Diagonal')
            ->and($result->buildingNumber)->toBe('405');
    });

    it('parses Pl. abbreviation', function (): void {
        $result = $this->service->split('Pl. Mayor 3', 'ES');

        expect($result->street)->toBe('Pl. Mayor')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses Pza. abbreviation', function (): void {
        $result = $this->service->split('Pza. de la Constitución 5', 'ES');

        expect($result->street)->toBe('Pza. de la Constitución')
            ->and($result->buildingNumber)->toBe('5');
    });

    // --- Format and apartment patterns ---

    it('parses with comma before number', function (): void {
        $result = $this->service->split('Calle Serrano, 27', 'ES');

        expect($result->street)->toBe('Calle Serrano')
            ->and($result->buildingNumber)->toBe('27');
    });

    it('parses with floor and Dcha.', function (): void {
        $result = $this->service->split('Calle Alcalá 100, 5º Dcha.', 'ES');

        expect($result->street)->toBe('Calle Alcalá')
            ->and($result->buildingNumber)->toBe('100')
            ->and($result->apartmentNumber)->toBe('5º Dcha.');
    });

    it('parses with floor and Izq.', function (): void {
        $result = $this->service->split('Avenida de América 37, 3º Izq.', 'ES');

        expect($result->street)->toBe('Avenida de América')
            ->and($result->buildingNumber)->toBe('37')
            ->and($result->apartmentNumber)->toBe('3º Izq.');
    });

    it('parses with escalera and floor', function (): void {
        $result = $this->service->split('Paseo de Gracia 25, Esc. A, 4º 1ª', 'ES');

        expect($result->street)->toBe('Paseo de Gracia')
            ->and($result->buildingNumber)->toBe('25')
            ->and($result->apartmentNumber)->toBe('Esc. A, 4º 1ª');
    });

    it('parses with Piso separator', function (): void {
        $result = $this->service->split('Calle Gran Vía 31, Piso 2', 'ES');

        expect($result->street)->toBe('Calle Gran Vía')
            ->and($result->buildingNumber)->toBe('31')
            ->and($result->apartmentNumber)->toBe('Piso 2');
    });

    it('parses ordinal with dot: 4.º B', function (): void {
        $result = $this->service->split('Calle Mayor 15, 4.º B', 'ES');

        expect($result->street)->toBe('Calle Mayor')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('4.º B');
    });

    // --- Building number with letter ---

    it('parses building number with letter suffix', function (): void {
        $result = $this->service->split('Calle Goya 12B', 'ES');

        expect($result->street)->toBe('Calle Goya')
            ->and($result->buildingNumber)->toBe('12B');
    });

    // --- Regional language street types ---

    it('parses Catalan Carrer de Pau Claris 150', function (): void {
        $result = $this->service->split('Carrer de Pau Claris 150', 'ES');

        expect($result->street)->toBe('Carrer de Pau Claris')
            ->and($result->buildingNumber)->toBe('150');
    });

    it('parses Basque Autonomia Kalea 22', function (): void {
        $result = $this->service->split('Autonomia Kalea 22', 'ES');

        expect($result->street)->toBe('Autonomia Kalea')
            ->and($result->buildingNumber)->toBe('22');
    });

    it('parses Galician Rúa do Vilar 15', function (): void {
        $result = $this->service->split('Rúa do Vilar 15', 'ES');

        expect($result->street)->toBe('Rúa do Vilar')
            ->and($result->buildingNumber)->toBe('15');
    });
});
