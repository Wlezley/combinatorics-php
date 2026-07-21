<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Enum\RankingOrder;
use Lishack\Combinatorics\Exception\DuplicateValueException;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Exception\UnsupportedValueTypeException;
use Lishack\Combinatorics\Exception\ValueNotFoundException;
use Lishack\Combinatorics\Ranking\CombinationRanker;
use Lishack\Combinatorics\Tests\Ranking\Fixture\CombinationRankerBackedEnum;
use Lishack\Combinatorics\Tests\Ranking\Fixture\CombinationRankerObject;
use Lishack\Combinatorics\Tests\Ranking\Fixture\CombinationRankerUnitEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(CombinationRanker::class)]
#[CoversMethod(Combinatorics::class, 'combinationRank')]
final class CombinationRankerTest extends TestCase
{
    /**
     * @param iterable<mixed> $universe
     * @param iterable<mixed> $combination
     */
    #[DataProvider('provideLexicographicRanks')]
    public function testLexicographicRank(
        iterable $universe,
        iterable $combination,
        int $expectedRank,
    ): void {
        self::assertEquals(
            BigInteger::of($expectedRank),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: $combination,
                order: RankingOrder::Lexicographic,
            ),
        );
    }

    /**
     * @param iterable<mixed> $universe
     * @param iterable<mixed> $combination
     */
    #[DataProvider('provideColexicographicRanks')]
    public function testColexicographicRank(
        iterable $universe,
        iterable $combination,
        int $expectedRank,
    ): void {
        self::assertEquals(
            BigInteger::of($expectedRank),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: $combination,
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    public function testRanksAllGeneratedCombinations(): void
    {
        $universe = ['A', 'B', 'C', 'D', 'E'];

        foreach (range(0, count($universe)) as $k) {
            $expectedRank = 0;

            foreach (Combinatorics::combinations($universe, $k) as $combination) {
                self::assertEquals(
                    BigInteger::of($expectedRank),
                    Combinatorics::combinationRank(
                        universe: $universe,
                        combination: $combination,
                        order: RankingOrder::Lexicographic,
                    ),
                );

                ++$expectedRank;
            }
        }
    }

    public function testAssociativeUniverse(): void
    {
        $universe = [
            10 => 'A',
            20 => 'B',
            30 => 'C',
            40 => 'D',
        ];

        self::assertEquals(
            BigInteger::of(4),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: ['B', 'D'],
            ),
        );

        self::assertEquals(
            BigInteger::of(2),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: ['A', 'D'],
                order: RankingOrder::Lexicographic,
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: ['A', 'D'],
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    public function testGeneratorUniverse(): void
    {
        self::assertEquals(
            BigInteger::of(4),
            Combinatorics::combinationRank(
                universe: self::createGenerator('A', 'B', 'C', 'D'),
                combination: ['B', 'D'],
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::combinationRank(
                universe: self::createGenerator('A', 'B', 'C', 'D'),
                combination: ['A', 'D'],
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    public function testGeneratorCombination(): void
    {
        self::assertEquals(
            BigInteger::of(4),
            Combinatorics::combinationRank(
                universe: ['A', 'B', 'C', 'D'],
                combination: self::createGenerator('B', 'D'),
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::combinationRank(
                universe: ['A', 'B', 'C', 'D'],
                combination: self::createGenerator('A', 'D'),
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    public function testBackedEnum(): void
    {
        $universe = [
            CombinationRankerBackedEnum::A,
            CombinationRankerBackedEnum::B,
            CombinationRankerBackedEnum::C,
            CombinationRankerBackedEnum::D,
        ];

        self::assertEquals(
            BigInteger::of(4),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [
                    CombinationRankerBackedEnum::B,
                    CombinationRankerBackedEnum::D,
                ],
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [
                    CombinationRankerBackedEnum::A,
                    CombinationRankerBackedEnum::D,
                ],
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    public function testUnitEnum(): void
    {
        $universe = [
            CombinationRankerUnitEnum::A,
            CombinationRankerUnitEnum::B,
            CombinationRankerUnitEnum::C,
            CombinationRankerUnitEnum::D,
        ];

        self::assertEquals(
            BigInteger::of(4),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [
                    CombinationRankerUnitEnum::B,
                    CombinationRankerUnitEnum::D,
                ],
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [
                    CombinationRankerUnitEnum::A,
                    CombinationRankerUnitEnum::D,
                ],
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    /**
     * Tests ranking objects using a custom key selector.
     */
    public function testObjectCallback(): void
    {
        $universe = [
            new CombinationRankerObject(10),
            new CombinationRankerObject(20),
            new CombinationRankerObject(30),
            new CombinationRankerObject(40),
        ];

        self::assertEquals(
            BigInteger::of(4),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [
                    $universe[1],
                    $universe[3],
                ],
                keySelector: static fn (CombinationRankerObject $object): int => $object->id,
            ),
        );

        self::assertEquals(
            BigInteger::of(3),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [
                    $universe[0],
                    $universe[3],
                ],
                order: RankingOrder::Colexicographic,
                keySelector: static fn (CombinationRankerObject $object): int => $object->id,
            ),
        );
    }

    public function testUnsupportedObjectWithoutCallbackThrowsException(): void
    {
        $this->expectException(UnsupportedValueTypeException::class);

        Combinatorics::combinationRank(
            universe: [
                new CombinationRankerObject(10),
                new CombinationRankerObject(20),
                new CombinationRankerObject(30),
            ],
            combination: [
                new CombinationRankerObject(10),
                new CombinationRankerObject(20),
            ],
        );
    }

    public function testDuplicateUniverseThrowsException(): void
    {
        $this->expectException(DuplicateValueException::class);

        Combinatorics::combinationRank(
            universe: [
                'A',
                'B',
                'B',
                'C',
            ],
            combination: [
                'A',
                'C',
            ],
        );
    }

    public function testDuplicateCombinationThrowsException(): void
    {
        $this->expectException(DuplicateValueException::class);

        Combinatorics::combinationRank(
            universe: [
                'A',
                'B',
                'C',
                'D',
            ],
            combination: [
                'A',
                'A',
            ],
        );
    }

    public function testValueNotFoundThrowsException(): void
    {
        $this->expectException(ValueNotFoundException::class);

        Combinatorics::combinationRank(
            universe: [
                'A',
                'B',
                'C',
                'D',
            ],
            combination: [
                'A',
                'E',
            ],
        );
    }

    public function testInvalidCombinationOrderThrowsException(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        Combinatorics::combinationRank(
            universe: [
                'A',
                'B',
                'C',
                'D',
            ],
            combination: [
                'C',
                'A',
            ],
        );
    }

    public function testEmptyCombination(): void
    {
        $universe = ['A', 'B', 'C', 'D'];

        self::assertEquals(
            BigInteger::zero(),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [],
            ),
        );

        self::assertEquals(
            BigInteger::zero(),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: [],
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    public function testFullCombination(): void
    {
        $universe = ['A', 'B', 'C', 'D'];

        self::assertEquals(
            BigInteger::zero(),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: $universe,
            ),
        );

        self::assertEquals(
            BigInteger::zero(),
            Combinatorics::combinationRank(
                universe: $universe,
                combination: $universe,
                order: RankingOrder::Colexicographic,
            ),
        );
    }

    /**
     * @return iterable<string, array{
     *     universe: list<string>,
     *     combination: list<string>,
     *     expectedRank: int,
     * }>
     */
    public static function provideLexicographicRanks(): iterable
    {
        $universe = ['A', 'B', 'C', 'D'];

        yield 'Empty combination' => [
            'universe' => $universe,
            'combination' => [],
            'expectedRank' => 0,
        ];

        yield 'Single A' => [
            'universe' => $universe,
            'combination' => ['A'],
            'expectedRank' => 0,
        ];

        yield 'Single B' => [
            'universe' => $universe,
            'combination' => ['B'],
            'expectedRank' => 1,
        ];

        yield 'Single C' => [
            'universe' => $universe,
            'combination' => ['C'],
            'expectedRank' => 2,
        ];

        yield 'Single D' => [
            'universe' => $universe,
            'combination' => ['D'],
            'expectedRank' => 3,
        ];

        yield 'AB' => [
            'universe' => $universe,
            'combination' => ['A', 'B'],
            'expectedRank' => 0,
        ];

        yield 'AC' => [
            'universe' => $universe,
            'combination' => ['A', 'C'],
            'expectedRank' => 1,
        ];

        yield 'AD' => [
            'universe' => $universe,
            'combination' => ['A', 'D'],
            'expectedRank' => 2,
        ];

        yield 'BC' => [
            'universe' => $universe,
            'combination' => ['B', 'C'],
            'expectedRank' => 3,
        ];

        yield 'BD' => [
            'universe' => $universe,
            'combination' => ['B', 'D'],
            'expectedRank' => 4,
        ];

        yield 'CD' => [
            'universe' => $universe,
            'combination' => ['C', 'D'],
            'expectedRank' => 5,
        ];

        yield 'ABC' => [
            'universe' => $universe,
            'combination' => ['A', 'B', 'C'],
            'expectedRank' => 0,
        ];

        yield 'ABD' => [
            'universe' => $universe,
            'combination' => ['A', 'B', 'D'],
            'expectedRank' => 1,
        ];

        yield 'ACD' => [
            'universe' => $universe,
            'combination' => ['A', 'C', 'D'],
            'expectedRank' => 2,
        ];

        yield 'BCD' => [
            'universe' => $universe,
            'combination' => ['B', 'C', 'D'],
            'expectedRank' => 3,
        ];

        yield 'Full combination' => [
            'universe' => $universe,
            'combination' => ['A', 'B', 'C', 'D'],
            'expectedRank' => 0,
        ];
    }

    /**
     * @return iterable<string, array{
     *     universe: list<string>,
     *     combination: list<string>,
     *     expectedRank: int,
     * }>
     */
    public static function provideColexicographicRanks(): iterable
    {
        $universe = ['A', 'B', 'C', 'D'];

        yield 'Empty combination' => [
            'universe' => $universe,
            'combination' => [],
            'expectedRank' => 0,
        ];

        yield 'Single A' => [
            'universe' => $universe,
            'combination' => ['A'],
            'expectedRank' => 0,
        ];

        yield 'Single B' => [
            'universe' => $universe,
            'combination' => ['B'],
            'expectedRank' => 1,
        ];

        yield 'Single C' => [
            'universe' => $universe,
            'combination' => ['C'],
            'expectedRank' => 2,
        ];

        yield 'Single D' => [
            'universe' => $universe,
            'combination' => ['D'],
            'expectedRank' => 3,
        ];

        yield 'AB' => [
            'universe' => $universe,
            'combination' => ['A', 'B'],
            'expectedRank' => 0,
        ];

        yield 'AC' => [
            'universe' => $universe,
            'combination' => ['A', 'C'],
            'expectedRank' => 1,
        ];

        yield 'BC' => [
            'universe' => $universe,
            'combination' => ['B', 'C'],
            'expectedRank' => 2,
        ];

        yield 'AD' => [
            'universe' => $universe,
            'combination' => ['A', 'D'],
            'expectedRank' => 3,
        ];

        yield 'BD' => [
            'universe' => $universe,
            'combination' => ['B', 'D'],
            'expectedRank' => 4,
        ];

        yield 'CD' => [
            'universe' => $universe,
            'combination' => ['C', 'D'],
            'expectedRank' => 5,
        ];

        yield 'ABC' => [
            'universe' => $universe,
            'combination' => ['A', 'B', 'C'],
            'expectedRank' => 0,
        ];

        yield 'ABD' => [
            'universe' => $universe,
            'combination' => ['A', 'B', 'D'],
            'expectedRank' => 1,
        ];

        yield 'ACD' => [
            'universe' => $universe,
            'combination' => ['A', 'C', 'D'],
            'expectedRank' => 2,
        ];

        yield 'BCD' => [
            'universe' => $universe,
            'combination' => ['B', 'C', 'D'],
            'expectedRank' => 3,
        ];

        yield 'Full combination' => [
            'universe' => $universe,
            'combination' => ['A', 'B', 'C', 'D'],
            'expectedRank' => 0,
        ];
    }

    /**
     * @return \Generator<int, string>
     */
    private static function createGenerator(string ...$values): \Generator
    {
        yield from $values;
    }
}
