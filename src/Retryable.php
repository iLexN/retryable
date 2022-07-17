<?php
declare(strict_types=1);

namespace Ilex\Retryable;

use Ilex\Retryable\Enum\FailAction;
use Ilex\Retryable\Exception\InvalidArgument;

final class Retryable
{

    /**
     * @param callable $process
     * @param callable $retryHandler
     * @param positive-int $maxAttempts
     * @param positive-int $wait
     */
    private function __construct(
        private $process,
        private $retryHandler,
        private readonly int $maxAttempts,
        private readonly int $wait,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function run(mixed ...$params): mixed
    {
        $count = 0;
        while (true) {
            $count++;
            try {
                return ($this->process)(... $params);
            } catch (\Throwable $throwable) {
                sleep($this->wait);

                $isStop = ($this->retryHandler)($throwable);
                if ($isStop === FailAction::Stop) {
                    throw $throwable;
                }
                if ($count >= $this->maxAttempts) {
                    throw $throwable;
                }
            }
        }
    }

    public static function new(
        callable $process,
        callable $retryHandler = null,
        int $maxAttempts = 1,
        int $wait = 1,
    ): self {

        if ($maxAttempts <= 0) {
            throw InvalidArgument::positiveInt('maxAttempts');
        }
        if ($wait <= 0) {
            throw InvalidArgument::positiveInt('wait');
        }

        if ($retryHandler === null) {
            $retryHandler = static function (): FailAction {
                return FailAction::Retry;
            };
        }

        return new Retryable(
            process: $process,
            retryHandler: $retryHandler,
            maxAttempts: $maxAttempts,
            wait: $wait
        );
    }
}
