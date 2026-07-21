<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Assert;

use Lishack\Combinatorics\Exception\DuplicateValueException;
use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;
use Lishack\Combinatorics\Exception\ValueNotFoundException;

final class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @throws InvalidCombinatoricsArgument
     */
    protected static function reportInvalidArgument(string $message): never
    {
        throw new InvalidCombinatoricsArgument($message);
    }

    /**
     * @param array<int|string, mixed> $values
     */
    public static function uniqueValue(array $values, int|string $key): void
    {
        if (array_key_exists($key, $values)) {
            throw new DuplicateValueException(
                sprintf(
                    'Duplicate value detected for key "%s".',
                    (string) $key,
                ),
            );
        }
    }

    /**
     * @param array<int|string, mixed> $values
     */
    public static function valueExists(array $values, int|string $key): void
    {
        if (!array_key_exists($key, $values)) {
            throw new ValueNotFoundException(
                sprintf(
                    'Value with key "%s" was not found.',
                    (string) $key,
                ),
            );
        }
    }
}
