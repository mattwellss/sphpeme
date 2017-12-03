<?php

namespace tests\Lisp;

use function Sphpeme\evaluate;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Sphpeme\Symbol;

class EvaluateTest extends TestCase
{
    /** @var ObjectProphecy */
    private $env;

    public function setUp()
    {
        $this->env = $this->prophesize(\stdClass::class);
    }

    public function testBasicEnvCall()
    {
        $this->env->{'+'} = function (...$args) {
            static::assertEquals([1, 2, 3], $args);
        };
        evaluate([new Symbol('+'), 1, 2, 3], $this->env->reveal());
    }

    public function testLambda()
    {
        $this->env->{'+'} = function (...$args) {
            static::assertEquals([1,2,3], $args);
        };

        $lambda = evaluate([new Symbol('lambda'), [], [new Symbol('+'), 1, 2, 3]], $this->env->reveal());

        static::assertInstanceOf(\Closure::class, $lambda);
        $lambda();
    }

    public function testDefine()
    {
        $env = new \stdClass();
        evaluate(['define', 'x', 10], $env);

        static::assertEquals(10, $env->x);
    }

    public function testIf()
    {
        $env = (object)[
            't' => true
        ];
        static::assertEquals(42, evaluate(['if', 't', 42, 0], $env));
    }
}