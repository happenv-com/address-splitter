<?php

declare(strict_types=1);

use Happenv\AddressSplitter\AddressSplitterService;

beforeEach(function (): void {
    $this->service = new AddressSplitterService;
});

describe('France (FR)', function (): void {
    it('parses number before Rue', function (): void {
        $result = $this->service->split('15 Rue de la Paix', 'FR');

        expect($result->street)->toBe('Rue de la Paix')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses number with bis suffix', function (): void {
        $result = $this->service->split('15bis Rue de la Paix', 'FR');

        expect($result->street)->toBe('Rue de la Paix')
            ->and($result->buildingNumber)->toBe('15bis');
    });

    it('parses Avenue', function (): void {
        $result = $this->service->split('23 Avenue des Champs-Élysées', 'FR');

        expect($result->street)->toBe('Avenue des Champs-Élysées')
            ->and($result->buildingNumber)->toBe('23');
    });

    it('parses Boulevard', function (): void {
        $result = $this->service->split('7 Boulevard Saint-Germain', 'FR');

        expect($result->street)->toBe('Boulevard Saint-Germain')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses Place with apartment', function (): void {
        $result = $this->service->split('3 Place de la Concorde, App. 5', 'FR');

        expect($result->street)->toBe('Place de la Concorde')
            ->and($result->buildingNumber)->toBe('3')
            ->and($result->apartmentNumber)->toBe('5');
    });

    it('parses Bte apartment indicator', function (): void {
        $result = $this->service->split('12 Allée des Lilas Bte 3', 'FR');

        expect($result->street)->toBe('Allée des Lilas')
            ->and($result->buildingNumber)->toBe('12')
            ->and($result->apartmentNumber)->toBe('3');
    });

    // --- Street type prefix coverage ---

    it('parses Chemin des Vignes', function (): void {
        $result = $this->service->split('5 Chemin des Vignes', 'FR');

        expect($result->street)->toBe('Chemin des Vignes')
            ->and($result->buildingNumber)->toBe('5');
    });

    it('parses Impasse du Moulin', function (): void {
        $result = $this->service->split('3 Impasse du Moulin', 'FR');

        expect($result->street)->toBe('Impasse du Moulin')
            ->and($result->buildingNumber)->toBe('3');
    });

    it('parses Passage du Commerce', function (): void {
        $result = $this->service->split('8 Passage du Commerce', 'FR');

        expect($result->street)->toBe('Passage du Commerce')
            ->and($result->buildingNumber)->toBe('8');
    });

    it('parses Cours Mirabeau', function (): void {
        $result = $this->service->split('42 Cours Mirabeau', 'FR');

        expect($result->street)->toBe('Cours Mirabeau')
            ->and($result->buildingNumber)->toBe('42');
    });

    it('parses Quai de la Tournelle', function (): void {
        $result = $this->service->split('17 Quai de la Tournelle', 'FR');

        expect($result->street)->toBe('Quai de la Tournelle')
            ->and($result->buildingNumber)->toBe('17');
    });

    it('parses Faubourg Saint-Honoré', function (): void {
        $result = $this->service->split('45 Faubourg Saint-Honoré', 'FR');

        expect($result->street)->toBe('Faubourg Saint-Honoré')
            ->and($result->buildingNumber)->toBe('45');
    });

    it('parses Sentier des Roses', function (): void {
        $result = $this->service->split('2 Sentier des Roses', 'FR');

        expect($result->street)->toBe('Sentier des Roses')
            ->and($result->buildingNumber)->toBe('2');
    });

    it('parses Route de Lyon', function (): void {
        $result = $this->service->split('120 Route de Lyon', 'FR');

        expect($result->street)->toBe('Route de Lyon')
            ->and($result->buildingNumber)->toBe('120');
    });

    it('parses Voie Georges Pompidou', function (): void {
        $result = $this->service->split('10 Voie Georges Pompidou', 'FR');

        expect($result->street)->toBe('Voie Georges Pompidou')
            ->and($result->buildingNumber)->toBe('10');
    });

    it('parses Square René Viviani', function (): void {
        $result = $this->service->split('6 Square René Viviani', 'FR');

        expect($result->street)->toBe('Square René Viviani')
            ->and($result->buildingNumber)->toBe('6');
    });

    it('parses Rond-Point des Champs-Élysées', function (): void {
        $result = $this->service->split('1 Rond-Point des Champs-Élysées', 'FR');

        expect($result->street)->toBe('Rond-Point des Champs-Élysées')
            ->and($result->buildingNumber)->toBe('1');
    });

    it('parses Carrefour de l\'Odéon', function (): void {
        $result = $this->service->split('4 Carrefour de l\'Odéon', 'FR');

        expect($result->street)->toBe('Carrefour de l\'Odéon')
            ->and($result->buildingNumber)->toBe('4');
    });

    it('parses Montée de la Grande Côte', function (): void {
        $result = $this->service->split('7 Montée de la Grande Côte', 'FR');

        expect($result->street)->toBe('Montée de la Grande Côte')
            ->and($result->buildingNumber)->toBe('7');
    });

    it('parses Traverse des Réformés', function (): void {
        $result = $this->service->split('15 Traverse des Réformés', 'FR');

        expect($result->street)->toBe('Traverse des Réformés')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses Cité Bergère', function (): void {
        $result = $this->service->split('3 Cité Bergère', 'FR');

        expect($result->street)->toBe('Cité Bergère')
            ->and($result->buildingNumber)->toBe('3');
    });

    // --- Building number variants ---

    it('parses ter suffix', function (): void {
        $result = $this->service->split('10ter Rue du Bac', 'FR');

        expect($result->street)->toBe('Rue du Bac')
            ->and($result->buildingNumber)->toBe('10ter');
    });

    it('parses quater suffix', function (): void {
        $result = $this->service->split('4quater Avenue Foch', 'FR');

        expect($result->street)->toBe('Avenue Foch')
            ->and($result->buildingNumber)->toBe('4quater');
    });

    // --- Abbreviation variants ---

    it('parses Av. abbreviation', function (): void {
        $result = $this->service->split('12 Av. Victor Hugo', 'FR');

        expect($result->street)->toBe('Av. Victor Hugo')
            ->and($result->buildingNumber)->toBe('12');
    });

    it('parses Blvd. abbreviation', function (): void {
        $result = $this->service->split('22 Blvd. Haussmann', 'FR');

        expect($result->street)->toBe('Blvd. Haussmann')
            ->and($result->buildingNumber)->toBe('22');
    });

    it('parses Rte. abbreviation', function (): void {
        $result = $this->service->split('50 Rte. de Versailles', 'FR');

        expect($result->street)->toBe('Rte. de Versailles')
            ->and($result->buildingNumber)->toBe('50');
    });

    it('parses Fbg. abbreviation', function (): void {
        $result = $this->service->split('3 Fbg. Saint-Antoine', 'FR');

        expect($result->street)->toBe('Fbg. Saint-Antoine')
            ->and($result->buildingNumber)->toBe('3');
    });

    // --- Apartment indicator variants ---

    it('parses Étage apartment indicator', function (): void {
        $result = $this->service->split('8 Rue Mouffetard, Ét. 3', 'FR');

        expect($result->street)->toBe('Rue Mouffetard')
            ->and($result->buildingNumber)->toBe('8')
            ->and($result->apartmentNumber)->toBe('3');
    });

    it('parses Appartement (full word)', function (): void {
        $result = $this->service->split('14 Rue Lepic, Appartement 6', 'FR');

        expect($result->street)->toBe('Rue Lepic')
            ->and($result->buildingNumber)->toBe('14')
            ->and($result->apartmentNumber)->toBe('6');
    });

    it('parses Porte apartment indicator', function (): void {
        $result = $this->service->split('20 Boulevard Voltaire, Porte 4', 'FR');

        expect($result->street)->toBe('Boulevard Voltaire')
            ->and($result->buildingNumber)->toBe('20')
            ->and($result->apartmentNumber)->toBe('4');
    });

    // --- Format variants ---

    it('parses comma after number before street', function (): void {
        $result = $this->service->split('15, Rue de Rivoli', 'FR');

        expect($result->street)->toBe('Rue de Rivoli')
            ->and($result->buildingNumber)->toBe('15');
    });

    it('parses bis with space before suffix', function (): void {
        $result = $this->service->split('15 bis Rue de la Paix', 'FR');

        expect($result->street)->toBe('Rue de la Paix')
            ->and($result->buildingNumber)->toBe('15bis');
    });
});

describe('Belgium (BE)', function (): void {
    it('uses French parser for Belgian address', function (): void {
        $result = $this->service->split('15 Rue Neuve', 'BE');

        expect($result->street)->toBe('Rue Neuve')
            ->and($result->buildingNumber)->toBe('15');
    });
});

describe('Luxembourg (LU)', function (): void {
    it('uses French parser for Luxembourg address', function (): void {
        $result = $this->service->split('8 Boulevard Royal', 'LU');

        expect($result->street)->toBe('Boulevard Royal')
            ->and($result->buildingNumber)->toBe('8');
    });
});
