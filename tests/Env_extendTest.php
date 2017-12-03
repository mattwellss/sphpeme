<?php

namespace tests\Sphpeme;

use Sphpeme\Env;
use function Sphpeme\env_extend;

use PHPUnit\Framework\TestCase;

class Env_extendTest extends TestCase
{
    public function testExtendedEnvCanOverwriteBase()
    {
        $env = new Env;
        $env->x = 10;
        $extended = env_extend($env, ['x' => 11]);
        static::assertEquals(11, $extended->x);
    }

    public function testExtendDoesNotOverwriteBaseDefinitions()
    {
        $env = new Env;
        $env->x = 10;
        env_extend($env, ['x' => 11]);
        static::assertEquals(10, $env->x);
    }
}