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
final class PowerSetGenerator implements IteratorAggregate
{
    /**
     * @var list<TValue>
     */
    private array $values;

    /**
     * @param iterable<TValue> $values
     */
    public function __construct(iterable $values)
    {
        if (is_array($values)) {
            $this->values = array_values($values);
        } else {
            $this->values = iterator_to_array($values, false);
        }
    }

    /**
     * @return Traversable<int, list<TValue>>
     */
    public function getIterator(): Traversable
    {
        $count = count($this->values);

        for ($k = 0; $k <= $count; ++$k) {
            yield from new CombinationGenerator(
                values: $this->values,
                k: $k,
            );
        }
    }
}
