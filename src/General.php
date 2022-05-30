<?php

declare(strict_types=1);

namespace Ilex\PackageName;

final class General
{

    /**
     * @param string $string
     */
    private function __construct(
        public readonly string $string,
    ) {
    }

    public static function create(string $string): self
    {
        return new self($string);
    }
}
