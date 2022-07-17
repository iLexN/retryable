<?php
declare(strict_types=1);

namespace Ilex\Retryable\Enum;

enum FailAction
{
    case Retry;

    case Stop;
}
