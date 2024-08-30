# maximaster/doctrine-migration-match-comaprator

```bash
composer require maximaster/doctrine-migration-comparators
```

# NumericComparator

Compares numeric parts of versons as numbers.

## MatchComparator

Comparator that prioritize versions which matches first on list of regexps.

**TIP**: You can use `softspring/doctrine-migrations-version-comparator` as equality
fallback comparator.

### Example

**config/packages/doctrine_migrations.yaml**

```yaml
doctrine_migrations:
    services:
        Doctrine\Migrations\Version\Comparator: Maximaster\DoctrineMigrationComparators\Comparator\MatchComparator
```

**services.yaml**

```yaml
    Maximaster\DoctrineMigrationComparators\Comparator\MatchComparator:
        arguments:
            -
                - ~MyProject\Process~
                - ~MyProject\Tests~
            - '@Maximaster\DoctrineMigrationComparators\Comparator\NumericComparator'
```

`MyProject\Process` migrations would be executed first, then `MyProject\Tests`.
