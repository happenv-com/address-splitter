<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('generic fallback', function (): void {
    it('parses street number slash apartment', function (): void {
        $result = $this->service->split('Unknown Street 15/3');

        expect($result->street)->toBe('Unknown Street')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses street number comma apartment', function (): void {
        $result = $this->service->split('Unknown Street 15, apt 3');

        expect($result->street)->toBe('Unknown Street')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('apt 3');
    });

    it('parses street number dash apartment', function (): void {
        $result = $this->service->split('Unknown Street 15-3');

        expect($result->street)->toBe('Unknown Street')
            ->and($result->buildingNumber)->toBe('15')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses number first format', function (): void {
        $result = $this->service->split('15 Unknown Street');

        expect($result->buildingNumber)->not->toBeNull();
    });
});
