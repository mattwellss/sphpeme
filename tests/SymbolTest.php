<?php

namespace tests\Sphpeme;

use PHPUnit\Framework\TestCase;
use Sphpeme\Symbol;

class SymbolTest extends TestCase
{
    public function testMakeSymbol()
    {
        $sym = Symbol::make('symbol');
        static::assertInstanceOf(Symbol::class, $sym);
        static::assertEquals('symbol', (string)$sym);
    }

    public function testExistingSymbolsAreReturned()
    {
        Symbol::$table = []; // reset because it's been messed with
        Symbol::make('symbol');
        Symbol::make('symbol');
        static::assertCount(1, Symbol::$table);
    }
}