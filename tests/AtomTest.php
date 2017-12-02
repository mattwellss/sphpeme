<?php

namespace tests\Lisp;

use function Sphpeme\atom;

use PHPUnit\Framework\TestCase;

class AtomTest extends TestCase
{
    public function testInt()
    {
        static::assertEquals(1, atom('1'));
    }

    public function testFloat()
    {
        static::assertEquals(3.3, atom('3.3'));
    }

    public function testString()
    {
        static::assertEquals('asdf', atom('asdf'));
    }
}