<?php

declare(strict_types=1);

namespace Maximaster\DoctrineMigrationComparators\Comparator;

use Doctrine\Migrations\Version\Comparator;
use Doctrine\Migrations\Version\Version;

/**
 * Prioritize those versions which matches first on listed regexps.
 */
class MatchComparator implements Comparator
{
    /** @var string[] */
    private array $matches;
    private ?Comparator $equalityFallback;

    /**
     * @param string[] $matches
     */
    public function __construct(array $matches, ?Comparator $equalityFallback = null)
    {
        $this->matches = $matches;
        $this->equalityFallback = $equalityFallback;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable) why:dependency
     */
    public function compare(Version $a, Version $b): int
    {
        $compare = $this->matchIndex($a) <=> $this->matchIndex($b);
        if ($compare === 0 && $this->equalityFallback !== null) {
            return $this->equalityFallback->compare($a, $b);
        }

        return $compare;
    }

    private function matchIndex(Version $version): int
    {
        $versionName = (string) $version;
        foreach ($this->matches as $matchIndex => $match) {
            if (preg_match($match, $versionName) === 1) {
                return $matchIndex;
            }
        }

        return PHP_INT_MAX;
    }
}
