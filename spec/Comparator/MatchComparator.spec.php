<?php

declare(strict_types=1);

use Doctrine\Migrations\Version\Version;
use Maximaster\DoctrineMigrationComparators\Comparator\MatchComparator;
use Maximaster\DoctrineMigrationComparators\Comparator\NumericComparator;

describe(MatchComparator::class, function (): void {
    it('should compare', function (): void {
        $versions = [
            new Version('Some\Namespace\E\Version1'),
            new Version('Some\Namespace\D\Version2'),
            new Version('Some\Namespace\C\Version3'),
            new Version('Some\Namespace\B\Version4'),
            new Version('Some\Namespace\A\Version5'),
        ];

        $comparator = new MatchComparator([
            '~\\\C\\\~',
            '~\\\A\\\~',
        ]);

        $sortedVersions = array_values($versions);
        usort($sortedVersions, [$comparator, 'compare']);

        expect(array_map('strval', $sortedVersions))->toBe([
            'Some\Namespace\C\Version3',
            'Some\Namespace\A\Version5',
            'Some\Namespace\E\Version1',
            'Some\Namespace\D\Version2',
            'Some\Namespace\B\Version4',
        ]);
    });

    it('should use fallback to compare equals', function (): void {
        $versions = [
            new Version('Some\Namespace\A\Version3'),
            new Version('Some\Namespace\A\Version2'),
            new Version('Some\Namespace\A\Version1'),
            new Version('Some\Namespace\B\Version1'),
            new Version('Some\Namespace\B\Version2'),
            new Version('Some\Namespace\B\Version3'),
        ];

        $comparator = new MatchComparator(
            [
                '~\\\B\\\~',
                '~\\\A\\\~',
            ],
            new NumericComparator(),
        );

        $sortedVersions = array_values($versions);
        usort($sortedVersions, [$comparator, 'compare']);

        expect(array_map('strval', $sortedVersions))->toBe([
            'Some\Namespace\B\Version1',
            'Some\Namespace\B\Version2',
            'Some\Namespace\B\Version3',
            'Some\Namespace\A\Version1',
            'Some\Namespace\A\Version2',
            'Some\Namespace\A\Version3',
        ]);
    });
});
