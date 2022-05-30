<?php

declare(strict_types=1);

namespace Ilex\PackageName\Tests;

use Ilex\PackageName\General;
use PHPUnit\Framework\TestCase;

final class GeneralTest extends TestCase
{

    public function testToJson(): void
    {
        $g = General::create('aa');
        self::assertEquals('aa', $g->string);
    }
}
