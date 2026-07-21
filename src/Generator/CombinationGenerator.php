<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Generator;

use Generator;
use IteratorAggregate;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Traversable;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<int, list<TValue>>
 */
final class CombinationGenerator implements IteratorAggregate
{
    /**
     * @var list<TValue>
     */
    private array $values;

    /**
     * @throws InvalidCombinatoricsArgument
     *
     * @param iterable<TValue> $values
     */
    public function __construct(
        iterable $values,
        private readonly int $k,
    ) {
        Assert::notNegativeInteger($this->k, 'Argument $k must be >= 0.');

        if (is_array($values)) {
            $this->values = array_values($values);
        } else {
            $this->values = iterator_to_array($values, false);
        }

        Assert::lessThanEq(
            $this->k,
            count($this->values),
            'Argument $k must be <= number of values.'
        );
    }

    /**
     * @return Traversable<int, list<TValue>>
     */
    public function getIterator(): Traversable
    {
        if ($this->k === 0) {
            yield [];
            return;
        }

        yield from $this->generate(
            start: 0,
            combination: [],
        );
    }

    /**
     * @param list<TValue> $combination
     *
     * @return Generator<int, list<TValue>>
     */
    private function generate(
        int $start,
        array $combination,
    ): Generator {
        if (count($combination) === $this->k) {
            yield $combination;
            return;
        }

        $remaining = $this->k - count($combination);
        $max = count($this->values) - $remaining;

        for ($i = $start; $i <= $max; ++$i) {
            $next = $combination;
            $next[] = $this->values[$i];

            yield from $this->generate(
                start: $i + 1,
                combination: $next,
            );
        }
    }
}
