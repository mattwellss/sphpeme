<?php

namespace tests\Sphpeme;

use function Sphpeme\atom;

use PHPUnit\Framework\TestCase;
use Sphpeme\Symbol;

class AtomTest extends TestCase
{
    public function testInt()
    {
        $one = atom(1);
        static::assertEquals(1, $one->getValue());
    }

    public function testFloat()
    {
        $threpointthree = atom('3.3');
        static::assertEquals(3.3, $threpointthree->getValue());
    }

    public function testString()
    {
        $asdf = atom('"asdf"');
        static::assertEquals('asdf', $asdf->getValue());
    }

    public function testBool()
    {
        $true = atom(true);
        static::assertEquals(true, $true->getValue());
    }

    public function testSymbol()
    {
        $sym = atom('symbol');
        static::assertInstanceOf(Symbol::class, $sym);
        static::assertEquals('symbol', $sym);
    }
}