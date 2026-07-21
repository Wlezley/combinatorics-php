<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Generator;

use Generator;
use IteratorAggregate;
use Traversable;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<int, list<TValue>>
 */
final class PermutationGenerator implements IteratorAggregate
{
    /**
     * @var array<int, TValue>
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
        if ($this->values === []) {
            yield [];
            return;
        }

        yield from $this->generate(count($this->values));
    }

    /**
     * @return Generator<int, list<TValue>>
     */
    private function generate(int $size): Generator
    {
        if ($size === 1) {
            /** @var list<TValue> $values */
            $values = $this->values;

            yield $values;
            return;
        }

        for ($i = 0; $i < $size; ++$i) {
            yield from $this->generate($size - 1);

            $swap = ($size % 2 === 0)
                ? $i
                : 0;

            [$this->values[$swap], $this->values[$size - 1]]
                = [$this->values[$size - 1], $this->values[$swap]];
        }
    }
}
