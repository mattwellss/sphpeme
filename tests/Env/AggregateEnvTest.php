<?php
namespace tests\Sphpeme\Env;

use Sphpeme\Env;
use Sphpeme\Env\AggregateEnv;
use PHPUnit\Framework\TestCase;

class AggregateEnvTest extends TestCase
{
    /** @var AggregateEnv */
    private $env;

    protected function setUp()
    {
        $this->env = new AggregateEnv;
    }

    public function testExtend()
    {
        $env = $this->prophesize(Env\EnvInterface::class);
        $env
            ->__call('__isset', ['test'])
            ->willReturn(true);

        $env->__call('__get', ['test'])
            ->willReturn(function () { return 'hello'; });

        $env2 = $this->prophesize(Env\EnvInterface::class);
        $env2
            ->__call('__isset', ['test'])
            ->willReturn(true);
        $myenv = $this->env->extend($env->reveal());

        $test = $myenv->test;

        static::assertInternalType('callable', $test);
        static::assertEquals('hello', $test());

        $env2->__call('__get', ['test'])
            ->willReturn(function () { return 'bye'; });
        $myenv2 = $myenv->extend($env2->reveal());

        $test = $myenv2->test;

        static::assertInternalType('callable', $test);
        static::assertEquals('bye', $test());
    }


    public function testHas()
    {
        $env = $this->prophesize(Env\EnvInterface::class);
        $env->__call('__isset', ['test'])
            ->willReturn(true);

        $env->__call('__isset', ['nope!'])
            ->willReturn(false);

        $myenv = $this->env->extend($env->reveal());

        static::assertTrue(isset($myenv->test));
        static::assertFalse(isset($myenv->{'nope!'}));
    }

    public function testConstructWithMultipleEnvs()
    {
        $env = $this->prophesize(Env\EnvInterface::class);
        $env
            ->__call('__isset', ['test'])
            ->willReturn(true);

        $env->__call('__get', ['test'])
            ->willReturn(function () { return 'hello'; });

        $env2 = $this->prophesize(Env\EnvInterface::class);
        $env2
            ->__call('__isset', ['test'])
            ->willReturn(true);


        $env2->__call('__get', ['test'])
            ->willReturn(function () { return 'bye'; });

        $subj = new AggregateEnv($env->reveal(), $env2->reveal());
        static::assertEquals('bye', ($subj->test)());
    }
}
