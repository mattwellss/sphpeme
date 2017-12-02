<?php

namespace tests\Lisp;

use function Sphpeme\parse;

use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{
    public function testParse()
    {
        static::assertEquals(['+', 1, 2, 3], parse(['(', '+', 1, 2, 3, ')']));
    }
}