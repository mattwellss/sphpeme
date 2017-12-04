<?php

namespace tests\Sphpeme;

use function Sphpeme\parse;

use PHPUnit\Framework\TestCase;
use Sphpeme\Scalar;
use Sphpeme\Symbol;

class ParseTest extends TestCase
{
    public function testParse()
    {
        $plus = $this->prophesize(Symbol::class);
        $plus->__toString()->willReturn('+');

        $parsed = parse(['(', '+', 1, 2, 3, ')']);
        static::assertInstanceOf(Symbol::class, $parsed[0]);
        static::assertEquals('+', $parsed[0]);
        static::assertEquals([new Scalar(1), new Scalar(2), new Scalar(3)], array_slice($parsed, 1));
    }
}