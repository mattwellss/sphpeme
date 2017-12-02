<?php

namespace tests\Lisp;

use function Sphpeme\env_extend;

use PHPUnit\Framework\TestCase;

class Env_extendTest extends TestCase
{
    public function testExtendedEnvCanOverwriteBase()
    {
        $env = (object)[
            'x' => 10
        ];
        $extended = env_extend($env, ['x' => 11]);
        static::assertEquals(11, $extended->x);
    }

    public function testExtendDoesNotOverwriteBaseDefinitions()
    {
        $env = (object)[
            'x' => 10
        ];
        env_extend($env, ['x' => 11]);
        static::assertEquals(10, $env->x);
    }
}