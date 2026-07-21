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
     * @param iterable<TValue> $values
     *
     * @throws InvalidCombinatoricsArgument
     */
    public function __construct(
        iterable $values,
        private readonly int $k,
        private readonly bool $allowRepetition = false,
    ) {
        Assert::notNegativeInteger($this->k, 'Argument $k must be >= 0.');

        if (is_array($values)) {
            $this->values = array_values($values);
        } else {
            $this->values = iterator_to_array($values, false);
        }

        if (!$this->allowRepetition) {
            Assert::lessThanEq(
                $this->k,
                count($this->values),
                'Argument $k must be <= number of values.'
            );
        }
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
        $combinationCount = count($combination);

        if ($combinationCount === $this->k) {
            yield $combination;
            return;
        }

        for ($i = $start; $i <= $this->maxIndex($combinationCount); ++$i) {
            $next = $combination;
            $next[] = $this->values[$i];

            yield from $this->generate(
                start: $this->nextStart($i),
                combination: $next,
            );
        }
    }

    private function nextStart(int $index): int
    {
        return $this->allowRepetition ? $index : $index + 1;
    }

    private function maxIndex(int $combinationCount): int
    {
        $valueCount = count($this->values);

        if ($this->allowRepetition) {
            return $valueCount - 1;
        }

        return $valueCount - ($this->k - $combinationCount);
    }
}
