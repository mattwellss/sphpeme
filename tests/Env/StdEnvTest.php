<?php

namespace Env;

use Sphpeme\Env\StdEnv;
use PHPUnit\Framework\TestCase;

class StdEnvTest extends TestCase
{
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
