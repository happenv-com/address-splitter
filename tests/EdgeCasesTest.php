<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('empty and edge cases', function (): void {
    it('returns empty street for empty input', function (): void {
        $result = $this->service->split('');

        expect($result->street)->toBe('')
            ->and($result->buildingNumber)->toBeNull()
            ->and($result->apartmentNumber)->toBeNull();
    });

    it('returns empty street for whitespace-only input', function (): void {
        $result = $this->service->split('   ');

        expect($result->street)->toBe('');
    });

    it('returns full address as street when no number found', function (): void {
        $result = $this->service->split('Ulica Bez Numeru', 'PL');

        expect($result->street)->toBe('Ulica Bez Numeru')
            ->and($result->buildingNumber)->toBeNull();
    });

    it('uses fallback parser when country code is null', function (): void {
        $result = $this->service->split('Some Street 15');

        expect($result->street)->toBe('Some Street')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('uses fallback parser for unknown country code', function (): void {
        $result = $this->service->split('Unknown Street 42', 'XX');

        expect($result->street)->toBe('Unknown Street')
            ->and($result->buildingNumber)->toBe('42');
    });

    it('handles country code case insensitivity', function (): void {
        $result = $this->service->split('ul. Marszałkowska 1', 'pl');

        expect($result->street)->toBe('ul. Marszałkowska')
            ->and($result->buildingNumber)->toBe('1');
    });
});
