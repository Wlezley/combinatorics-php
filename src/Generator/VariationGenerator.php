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
final class VariationGenerator implements IteratorAggregate
{
    /**
     * @var list<TValue>
     */
    private array $values;

    /**
     * @var array<int, bool>
     */
    private array $used = [];

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

        $this->used = array_fill(0, count($this->values), false);
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

        yield from $this->generate([]);
    }

    /**
     * @param list<TValue> $variation
     *
     * @return Generator<int, list<TValue>>
     */
    private function generate(array $variation): Generator
    {
        if (count($variation) === $this->k) {
            yield $variation;
            return;
        }

        foreach ($this->values as $index => $value) {
            if ($this->isUsed($index)) {
                continue;
            }

            $this->markUsed($index, true);

            $next = $variation;
            $next[] = $value;

            yield from $this->generate($next);

            $this->markUsed($index, false);
        }
    }

    private function isUsed(int $index): bool
    {
        return !$this->allowRepetition && $this->used[$index];
    }

    private function markUsed(int $index, bool $used): void
    {
        if (!$this->allowRepetition) {
            $this->used[$index] = $used;
        }
    }
}
