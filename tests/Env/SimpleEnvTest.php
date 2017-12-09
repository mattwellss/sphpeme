<?php

namespace Env;

use Sphpeme\Env\EnvInterface;
use Sphpeme\Env\SimpleEnv;
use PHPUnit\Framework\TestCase;

class SimpleEnvTest extends TestCase
{
    public function test__get()
    {
        $subj = new SimpleEnv(['a' => 'b']);
        static::assertEquals('b', $subj->a);
    }

    public function test__isset()
    {
        $subj = new SimpleEnv(['a' => 'b']);
        static::assertTrue(isset($subj->a));
    }
}
