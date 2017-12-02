<?php

namespace tests\Lisp;

use function Sphpeme\tokenize;

use PHPUnit\Framework\TestCase;

class TokenizeTest extends TestCase
{
    public function testTokenize()
    {
        static::assertEquals(['(', '+', 1, 2, 3, ')'], tokenize('(+ 1 2 3)'));
    }
}