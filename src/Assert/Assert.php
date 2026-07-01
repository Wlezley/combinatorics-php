<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Assert;

use Lishack\Combinatorics\Exception\InvalidCombinatoricsArgument;

class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @psalm-pure this method is not supposed to perform side effects
     *
     * @throws InvalidCombinatoricsArgument
     */
    protected static function reportInvalidArgument(string $message): never
    {
        throw new InvalidCombinatoricsArgument($message);
    }
}
