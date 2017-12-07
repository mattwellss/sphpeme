<?php

use Sphpeme\Env;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    /** @var Env */
    private $env;

    protected function setUp()
    {
        $this->env = (new Env)->extend(new class extends Env
        {
            public function has(string $prop): bool
            {
                return method_exists($this, $prop);
            }

            public function __get($name)
            {
                return [$this, $name];
            }

            public function test()
            {
                return 'hello';
            }
        });
    }

    public function testExtend()
    {
        $test = $this->env->test;

        static::assertInternalType('callable', $test);
        static::assertEquals('hello', $test());
    }

    public function testHas()
    {
        static::assertTrue($this->env->has('test'));
        static::assertFalse($this->env->has('nope!'));
    }
}
