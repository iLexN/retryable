<?php
declare(strict_types=1);

namespace Ilex\Retryable\Tests\Mock;

final class Fail
{

    private int $count;

    public function __construct()
    {
        $this->count = 0;
    }

    public function run(): void
    {
        $this->count++;
        throw new \Exception('fail');
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
