<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Tests\Ranking;

use Brick\Math\BigInteger;
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Ranking\CombinationUnranker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(CombinationUnranker::class)]
#[CoversMethod(Combinatorics::class, 'combinationUnrank')]
final class CombinationUnrankerTest extends TestCase
{
    public function testLexicographicUnranking(): void
    {
        $universe = ['A', 'B', 'C', 'D'];

        self::assertSame(
            ['A', 'B'],
            Combinatorics::combinationUnrank($universe, 0, 2),
        );

        self::assertSame(
            ['A', 'C'],
            Combinatorics::combinationUnrank($universe, 1, 2),
        );

        self::assertSame(
            ['A', 'D'],
            Combinatorics::combinationUnrank($universe, 2, 2),
        );

        self::assertSame(
            ['B', 'C'],
            Combinatorics::combinationUnrank($universe, 3, 2),
        );

        self::assertSame(
            ['B', 'D'],
            Combinatorics::combinationUnrank($universe, 4, 2),
        );

        self::assertSame(
            ['C', 'D'],
            Combinatorics::combinationUnrank($universe, 5, 2),
        );
    }

    public function testAcceptsBigIntegerRank(): void
    {
        self::assertSame(
            ['B', 'C'],
            Combinatorics::combinationUnrank(
                ['A', 'B', 'C', 'D'],
                BigInteger::of(3),
                2,
            ),
        );
    }

    public function testAcceptsStringRank(): void
    {
        self::assertSame(
            ['B', 'C'],
            Combinatorics::combinationUnrank(
                ['A', 'B', 'C', 'D'],
                '3',
                2,
            ),
        );
    }

    public function testReturnsEmptyCombination(): void
    {
        self::assertSame(
            [],
            Combinatorics::combinationUnrank(
                ['A', 'B', 'C'],
                0,
                0,
            ),
        );
    }

    public function testReturnsWholeUniverse(): void
    {
        self::assertSame(
            ['A', 'B', 'C'],
            Combinatorics::combinationUnrank(
                ['A', 'B', 'C'],
                0,
                3,
            ),
        );
    }

    public function testThrowsForNegativeRank(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        Combinatorics::combinationUnrank(
            ['A', 'B', 'C'],
            -1,
            2,
        );
    }

    public function testThrowsForRankOutOfRange(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        Combinatorics::combinationUnrank(
            ['A', 'B', 'C', 'D'],
            6,
            2,
        );
    }

    public function testThrowsForNegativeK(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        Combinatorics::combinationUnrank(
            ['A', 'B'],
            0,
            -1,
        );
    }

    public function testThrowsWhenKIsGreaterThanUniverseSize(): void
    {
        $this->expectException(InvalidCombinatoricsArgument::class);

        Combinatorics::combinationUnrank(
            ['A', 'B'],
            0,
            3,
        );
    }
}
