# PHP Combinatorics

A modern, type-safe combinatorics library for PHP 8.4+.

PHP Combinatorics provides efficient algorithms for counting, generating, ranking, and unranking combinatorial objects. It is designed for correctness, performance, and arbitrary-precision arithmetic using `Brick\Math`.

Unlike many combinatorics libraries, this project includes lazy generators, combination ranking and unranking algorithms, and exact calculations for extremely large numbers.

## Features

- 🚀 Modern PHP 8.4+ API
- 🔢 Arbitrary-precision arithmetic via `Brick\Math`
- 📊 Counting algorithms
    - Factorial
    - Binomial coefficient
    - Permutations
    - Variations
    - Combinations
    - Variations with repetition
    - Combinations with repetition
- ⚡ Lazy generators
    - Combinations
    - Combinations with repetition
    - Permutations
    - Variations
    - Variations with repetition
    - Power set
    - Cartesian product
- 🎯 Combination ranking
    - Lexicographic order
    - Colexicographic order
- 🔄 Combination unranking
- 🧩 Supports custom objects
- ✅ Strict typing
- ✅ PHPUnit tested
- ✅ PHPStan Level 8

---

## Requirements

- PHP 8.4 or later
- Brick\Math
- Webmozart Assert

---

## Installation

Install the package via Composer:

```bash
composer require lishack/combinatorics-php
```

---

## Why PHP Combinatorics?

Many existing PHP combinatorics libraries focus solely on counting functions or rely on native integers, which overflow for larger values.

PHP Combinatorics is designed to provide a complete toolkit for combinatorial mathematics with a modern, type-safe API.

### Highlights

- Exact calculations using arbitrary-precision integers.
- Memory-efficient lazy generators.
- Combination ranking and unranking.
- Modern PHP 8.4 API with strict typing.
- Thoroughly tested and statically analyzed.

---

# Documentation

The `Combinatorics` class provides a simple, static API for all supported combinatorial operations.

The library is divided into three main categories:

- **Counting** – Calculate the number of possible arrangements without generating them.
- **Generators** – Lazily generate combinatorial objects one at a time.
- **Ranking** – Convert combinations to numeric ranks and reconstruct them from those ranks.

All counting methods return a `Brick\Math\BigInteger`, allowing exact calculations without integer overflow.

## Counting

Counting methods calculate the number of possible results without generating them.

| Method | Description |
|--------|-------------|
| `factorial()` | Calculates the factorial of a non-negative integer. |
| `binomial()` | Calculates the binomial coefficient C(n, k). |
| `permutationsCount()` | Calculates the number of permutations. |
| `variationsCount()` | Calculates the number of variations without repetition. |
| `variationsWithRepetitionCount()` | Calculates the number of variations with repetition. |
| `combinationsCount()` | Calculates the number of combinations without repetition. |
| `combinationsWithRepetitionCount()` | Calculates the number of combinations with repetition. |

## Generators

Generator methods produce combinatorial objects lazily.

Instead of allocating every result in memory, values are generated only when requested during iteration.

| Method | Description |
|--------|-------------|
| `combinations()` | Generates combinations without repetition. |
| `combinationsWithRepetition()` | Generates combinations with repetition. |
| `permutations()` | Generates all permutations. |
| `variations()` | Generates variations without repetition. |
| `variationsWithRepetition()` | Generates variations with repetition. |
| `powerSet()` | Generates the power set. |
| `cartesianProduct()` | Generates the Cartesian product of multiple sets. |

## Ranking

Ranking methods assign unique numeric identifiers to combinations.

These methods are useful when combinations need to be stored, indexed, transferred, or reconstructed efficiently.

| Method | Description |
|--------|-------------|
| `combinationRank()` | Calculates the rank of a combination. |
| `combinationUnrank()` | Restores a combination from its rank. |

---

# Examples

## Counting

### Factorial

```php
use Lishack\Combinatorics\Combinatorics;

$result = Combinatorics::factorial(10);

echo $result;
```

Output

```text
3628800
```

---

### Binomial Coefficient

```php
$result = Combinatorics::binomial(5, 2);

echo $result;
```

Output

```text
10
```

---

### Permutations Count

```php
$result = Combinatorics::permutationsCount(5);

echo $result;
```

Output

```text
120
```

---

### Variations Count

```php
$result = Combinatorics::variationsCount(5, 3);

echo $result;
```

Output

```text
60
```

---

### Variations Count With Repetition

```php
$result = Combinatorics::variationsWithRepetitionCount(5, 3);

echo $result;
```

Output

```text
125
```

---

### Combinations Count

```php
$result = Combinatorics::combinationsCount(52, 5);

echo $result;
```

Output

```text
2598960
```

---

### Combinations Count With Repetition

```php
$result = Combinatorics::combinationsWithRepetitionCount(5, 3);

echo $result;
```

Output

```text
35
```

## Generators

### Combinations

```php
use Lishack\Combinatorics\Combinatorics;

foreach (Combinatorics::combinations(['A', 'B', 'C', 'D'], 2) as $combination) {
    print_r($combination);
}
```

Output

```text
Array
(
    [0] => A
    [1] => B
)

Array
(
    [0] => A
    [1] => C
)

Array
(
    [0] => A
    [1] => D
)

Array
(
    [0] => B
    [1] => C
)

Array
(
    [0] => B
    [1] => D
)

Array
(
    [0] => C
    [1] => D
)
```

---

### Combinations With Repetition

```php
foreach (Combinatorics::combinationsWithRepetition(['A', 'B', 'C'], 2) as $combination) {
    print_r($combination);
}
```

Output

```text
Array
(
    [0] => A
    [1] => A
)

Array
(
    [0] => A
    [1] => B
)

Array
(
    [0] => A
    [1] => C
)

Array
(
    [0] => B
    [1] => B
)

Array
(
    [0] => B
    [1] => C
)

Array
(
    [0] => C
    [1] => C
)
```

---

### Permutations

```php
foreach (Combinatorics::permutations(['A', 'B', 'C']) as $permutation) {
    print_r($permutation);
}
```

Output

```text
Array
(
    [0] => A
    [1] => B
    [2] => C
)

Array
(
    [0] => A
    [1] => C
    [2] => B
)

Array
(
    [0] => B
    [1] => A
    [2] => C
)

Array
(
    [0] => B
    [1] => C
    [2] => A
)

Array
(
    [0] => C
    [1] => A
    [2] => B
)

Array
(
    [0] => C
    [1] => B
    [2] => A
)
```

---

### Variations

```php
foreach (Combinatorics::variations(['A', 'B', 'C'], 2) as $variation) {
    print_r($variation);
}
```

Output

```text
Array
(
    [0] => A
    [1] => B
)

Array
(
    [0] => A
    [1] => C
)

Array
(
    [0] => B
    [1] => A
)

Array
(
    [0] => B
    [1] => C
)

Array
(
    [0] => C
    [1] => A
)

Array
(
    [0] => C
    [1] => B
)
```

---

### Variations With Repetition

```php
foreach (Combinatorics::variationsWithRepetition(['A', 'B'], 2) as $variation) {
    print_r($variation);
}
```

Output

```text
Array
(
    [0] => A
    [1] => A
)

Array
(
    [0] => A
    [1] => B
)

Array
(
    [0] => B
    [1] => A
)

Array
(
    [0] => B
    [1] => B
)
```

---

### Power Set

```php
foreach (Combinatorics::powerSet(['A', 'B', 'C']) as $subset) {
    print_r($subset);
}
```

Output

```text
Array
(
)

Array
(
    [0] => A
)

Array
(
    [0] => B
)

Array
(
    [0] => C
)

Array
(
    [0] => A
    [1] => B
)

Array
(
    [0] => A
    [1] => C
)

Array
(
    [0] => B
    [1] => C
)

Array
(
    [0] => A
    [1] => B
    [2] => C
)
```

---

### Cartesian Product

```php
$sets = [
    ['Red', 'Blue'],
    ['S', 'M'],
];

foreach (Combinatorics::cartesianProduct($sets) as $product) {
    print_r($product);
}
```

Output

```text
Array
(
    [0] => Red
    [1] => S
)

Array
(
    [0] => Red
    [1] => M
)

Array
(
    [0] => Blue
    [1] => S
)

Array
(
    [0] => Blue
    [1] => M
)
```

## Ranking

### Combination Rank (Lexicographic)

```php
use Lishack\Combinatorics\Combinatorics;
use Lishack\Combinatorics\Enum\RankingOrder;

$rank = Combinatorics::combinationRank(
    universe: ['A', 'B', 'C', 'D'],
    combination: ['B', 'D'],
    order: RankingOrder::Lexicographic,
);

echo $rank;
```

Output

```text
4
```

The combinations are ordered as follows:

| Rank | Combination |
|-----:|-------------|
| 0 | [A, B] |
| 1 | [A, C] |
| 2 | [A, D] |
| 3 | [B, C] |
| 4 | [B, D] |
| 5 | [C, D] |

---

### Combination Rank (Colexicographic)

```php
$rank = Combinatorics::combinationRank(
    universe: ['A', 'B', 'C', 'D'],
    combination: ['B', 'D'],
    order: RankingOrder::Colexicographic,
);

echo $rank;
```

Output

```text
4
```

The combinations are ordered as follows:

| Rank | Combination |
|-----:|-------------|
| 0 | [A, B] |
| 1 | [A, C] |
| 2 | [B, C] |
| 3 | [A, D] |
| 4 | [B, D] |
| 5 | [C, D] |

---

### Combination Unrank

```php
$combination = Combinatorics::combinationUnrank(
    universe: ['A', 'B', 'C', 'D'],
    rank: 4,
    k: 2,
);

print_r($combination);
```

Output

```text
Array
(
    [0] => B
    [1] => D
)
```

---

### Ranking Custom Objects

Objects can be ranked by providing a key selector.

```php
$users = [
    new User(10, 'Alice'),
    new User(20, 'Bob'),
    new User(30, 'Charlie'),
    new User(40, 'David'),
];

$rank = Combinatorics::combinationRank(
    universe: $users,
    combination: [$users[1], $users[3]],
    keySelector: static fn (User $user): int => $user->id,
);

echo $rank;
```

Output

```text
4
```

---

## Why Lazy Generation?

All generators return results one at a time.

Instead of allocating every possible result in memory, values are produced only when requested by the iterator.

This makes the library suitable for working with very large combinatorial spaces where generating every result at once would be impractical.
