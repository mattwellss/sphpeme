<?php

namespace tests\Lisp;

use PHPUnit\Framework\TestCase;
use Sphpeme\Symbol;

class SymbolTest extends TestCase
{
    public function testThisForTheKarma()
    {
        $value = 'asdf';
        static::assertEquals($value, (new Symbol($value))->__toString());
    }
}