<?php
declare(strict_types=1);

namespace Ilex\Retryable\Tests\Mock;

use Ilex\Retryable\Enum\FailAction;

final class FailStop
{
    private int $count;

    public function __construct()
    {
        $this->count = 0;
    }

    public function run(): void
    {
        $this->count++;
        throw new \Exception('fail2');
    }

    public function stop(): FailAction
    {
        return FailAction::Stop;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
