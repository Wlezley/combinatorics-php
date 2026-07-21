<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Generator;

use IteratorAggregate;
use Traversable;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<int, list<TValue>>
 */
final class CartesianProductGenerator implements IteratorAggregate
{
    /**
     * @var list<list<TValue>>
     */
    private array $sets = [];

    /**
     * @var array<int, int>
     */
    private array $radices = [];

    private bool $containsEmptySet = false;

    /**
     * @param iterable<iterable<TValue>> $sets Source sets.
     */
    public function __construct(iterable $sets)
    {
        foreach ($sets as $set) {
            $values = is_array($set)
                ? array_values($set)
                : iterator_to_array($set, false);

            $this->sets[] = $values;
            $this->radices[] = count($values);

            if ($values === []) {
                $this->containsEmptySet = true;
            }
        }
    }

    /**
     * @return Traversable<int, list<TValue>>
     */
    public function getIterator(): Traversable
    {
        if ($this->sets === []) {
            yield [];
            return;
        }

        if ($this->containsEmptySet) {
            return;
        }

        /** @var non-empty-array<int, int> $indices */
        $indices = array_fill(0, count($this->sets), 0);

        while (true) {
            $result = [];

            foreach ($indices as $dimension => $index) {
                $result[] = $this->sets[$dimension][$index];
            }

            yield $result;

            $indices = $this->nextIndices($indices);

            if ($indices === null) {
                return;
            }
        }
    }

    /**
     * Advances the mixed-radix counter.
     *
     * Returns null when all combinations have been generated.
     *
     * @param non-empty-array<int, int> $indices
     *
     * @return non-empty-array<int, int>|null
     */
    private function nextIndices(array $indices): ?array
    {
        for ($dimension = count($indices) - 1; $dimension >= 0; --$dimension) {
            if (++$indices[$dimension] < $this->radices[$dimension]) {
                return $indices;
            }

            $indices[$dimension] = 0;
        }

        return null;
    }
}
