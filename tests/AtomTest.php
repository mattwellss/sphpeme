<?php

namespace tests\Sphpeme;

use function Sphpeme\atom;

use PHPUnit\Framework\TestCase;
use Sphpeme\Symbol;

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
        static::assertEquals('asdf', atom('"asdf"'));
    }

    public function testSymbol()
    {
        $sym = atom('symbol');
        static::assertInstanceOf(Symbol::class, $sym);
        static::assertEquals('symbol', $sym);
    }
}