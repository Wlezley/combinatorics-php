<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Assert;

use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;

final class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @throws InvalidCombinatoricsArgument
     */
    protected static function reportInvalidArgument(string $message): never
    {
        throw new InvalidCombinatoricsArgument($message);
    }
}
