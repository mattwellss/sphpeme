<?php

namespace Env;

use Sphpeme\Env\StdEnv;
use PHPUnit\Framework\TestCase;

class StdEnvTest extends TestCase
{

    public function test__get()
    {
        $env = new StdEnv();
        static::assertEquals(M_PI, $env->pi);
        static::assertInternalType('callable', $env->{'+'});
    }

    public function test__isset()
    {
        $env = new StdEnv();
        static::assertTrue(isset($env->pi));
    }

    public function testAdd()
    {
        $env = new StdEnv();
        static::assertEquals(2, $env->add(1, 1));
        static::assertEquals(6, $env->add(1, 2, 3));
    }

    public function testSubtract()
    {
        $env = new StdEnv();
        static::assertEquals(0, $env->subtract(1, 1));
        static::assertEquals(-1, $env->subtract(3, 2, 1, 1));
    }

    public function testMultiply()
    {
        $env = new StdEnv();
        static::assertEquals(1, $env->multiply(1, 1));
        static::assertEquals(6, $env->multiply(3, 2, 1, 1));
    }

    public function testDivide()
    {
        $env = new StdEnv();
        static::assertEquals(2, $env->divide(4, 2));
    }

    public function testGt()
    {
        $env = new StdEnv();
        static::assertTrue($env->gt(2, 1));
    }

    public function testLt()
    {
        $env = new StdEnv();
        static::assertTrue($env->lt(1, 2));
    }

    public function testGte()
    {
        $env = new StdEnv();
        static::assertTrue($env->gte(2, 2));
        static::assertTrue($env->gte(3, 2));
    }

    public function testLte()
    {
        $env = new StdEnv();
        static::assertTrue($env->lte(2, 2));
        static::assertTrue($env->lte(2, 3));
    }

    public function testIsEqual()
    {
        $env = new StdEnv();
        static::assertTrue($env->isEqual(2, 2));
        static::assertTrue($env->isEqual(2, '2'));
    }

    public function testIsEquiv()
    {
        $env = new StdEnv();
        static::assertTrue($env->isEquiv(2, 2));
        static::assertFalse($env->isEquiv(2, '2'));
    }

    public function testList()
    {
        $env = new StdEnv();
        static::assertEquals([1,2], $env->_list(1, 2));
    }

    public function testCar()
    {
        $env = new StdEnv();
        static::assertEquals(1, $env->car([1]));
    }

    public function testCdr()
    {
        $env = new StdEnv();
        static::assertEquals([2], $env->cdr([1,2]));
    }

    public function testDisplay()
    {
        $env = new StdEnv();
        ob_start();
        $env->display('hello');
        static::assertEquals('hello', ob_get_clean());
        ob_start();
        $env->display([1,2,3]);
        static::assertEquals(var_export([1,2,3], true), ob_get_clean());
    }

    public function testNewline()
    {
        $env = new StdEnv();
        ob_start();
        $env->newline();
        static::assertEquals(PHP_EOL, ob_get_clean());
    }
}
