<?php
declare(strict_types=1);

namespace Ilex\Retryable\Exception;

final class InvalidArgument extends \InvalidArgumentException
{
    public static function positiveInt(string $name): InvalidArgument
    {
        $message = "$name value should be positive integer";
        return new self($message);
    }
}
