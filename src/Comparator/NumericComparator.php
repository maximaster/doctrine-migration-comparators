<?php

declare(strict_types=1);

namespace Maximaster\DoctrineMigrationComparators\Comparator;

use Doctrine\Migrations\Version\Comparator;
use Doctrine\Migrations\Version\Version;
use InvalidArgumentException;
use RuntimeException;

/**
 * Compares versions to order them by number in version name.
 */
class NumericComparator implements Comparator
{
    private ?Comparator $equalityFallback;

    public function __construct(?Comparator $equalityFallback = null)
    {
        $this->equalityFallback = $equalityFallback;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.ShortVariable) why:dependency
     */
    public function compare(Version $a, Version $b): int
    {
        $compare = $this->tailNumber($a) <=> $this->tailNumber($b);
        if ($compare === 0 && $this->equalityFallback !== null) {
            return $this->equalityFallback->compare($a, $b);
        }

        return $compare;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function tailNumber(Version $version): int
    {
        $matches = [];
        if (preg_match('/(\d+)$/', (string) $version, $matches) === false) {
            throw new InvalidArgumentException(
                sprintf('Versions without numbers are not supported, given: %s', $version)
            );
        }

        $value = (int) $matches[1];
        if ((string) $value !== $matches[1]) {
            throw new RuntimeException(sprintf('Number in "%s" is too big to be casted into integer', $matches[1]));
        }

        return $value;
    }
}
