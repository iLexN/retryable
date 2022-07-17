<?php

declare(strict_types=1);

namespace Ilex\Retryable\Tests;

use Ilex\Retryable\Exception\InvalidArgument;
use Ilex\Retryable\General;
use Ilex\Retryable\Retryable;
use Ilex\Retryable\Tests\Mock\Fail;
use Ilex\Retryable\Tests\Mock\FailStop;
use PHPUnit\Framework\TestCase;

final class RetryableTest extends TestCase
{

    public function testRun(): void
    {
        $retry = Retryable::new(static function (int $a, int $b, string $c) {
            self::assertSame(1, $a);
            self::assertSame(2, $b);
            self::assertSame('a', $c);
            return 1;
        }, static function (\Exception $e) {
            self::assertInstanceOf(\Exception::class, $e);
        });

        $result = $retry->run(1, 2, 'a');
        self::assertSame(1, $result);

        $a = [
            'a' => 1,
            'b' => 2,
            'c' => 'a',
        ];
        $result = $retry->run(... $a);
        self::assertSame(1, $result);

        $a = [
            'b' => 2,
            'c' => 'a',
            'a' => 1,
        ];
        $result = $retry->run(... $a);
        self::assertSame(1, $result);
    }

    public function testInvalidArgumentAttempts(): void
    {
        $this->expectException(InvalidArgument::class);

        Retryable::new(static function (int $a, int $b, string $c) {
            self::assertSame(1, $a);
            self::assertSame(2, $b);
            self::assertSame('a', $c);
        }, static function (\Exception $e) {
            self::assertInstanceOf(\Exception::class, $e);
        }, 0);
    }

    public function testInvalidArgumentWait(): void
    {

        $this->expectException(InvalidArgument::class);

        Retryable::new(static function (int $a, int $b, string $c) {
            self::assertSame(1, $a);
            self::assertSame(2, $b);
            self::assertSame('a', $c);
        }, static function (\Exception $e) {
            self::assertInstanceOf(\Exception::class, $e);
        }, 5, 0);
    }

    public function testFailMaxAttempts(): void
    {

        $cb = new Fail();
        $retry = Retryable::new([$cb, 'run'], null, 5, 1);

        try {
            $result = $retry->run(1);
        } catch (\Throwable $throwable) {
            self::assertSame(5, $cb->getCount());
            self::assertInstanceOf(\Exception::class, $throwable);
        }

    }

    public function testFailStop(): void
    {

        $cb = new FailStop();
        $retry = Retryable::new([$cb, 'run'], [$cb, 'stop'], 5, 1);

        try {
            $result = $retry->run(1);
        } catch (\Throwable $throwable) {
            self::assertInstanceOf(\Exception::class, $throwable);
            self::assertSame(1, $cb->getCount());
        }

    }
}
