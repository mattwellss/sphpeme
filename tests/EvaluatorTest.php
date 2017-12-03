<?php

namespace tests\Sphpeme;

use Prophecy\Argument;
use Sphpeme\Env;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class EvaluatorTest extends TestCase
{
    /** @var ObjectProphecy */
    private $env;
    /** @var Evaluator */
    private $subj;

    public function setUp()
    {
        $this->env = $this->prophesize(Env::class);
        $this->subj = new Evaluator;
    }

    public function testBasicEnvCall()
    {
        $this->env->{'+'} = function (...$args) {
            static::assertEquals([1, 2, 3], $args);
        };
        ($this->subj)([new Symbol('+'), 1, 2, 3], $this->env->reveal());
    }

    public function testSymbol()
    {
        // Define a symbol and its existence in env
        $v = $this->prophesize(Symbol::class);
        $v->__toString()->willReturn('hello');
        $this->env->hello = true;

        static::assertTrue(($this->subj)($v->reveal(), $this->env->reveal()));
    }

    public function testSpecialForms()
    {
        $special = $this->prophesize(Evaluator\SpecialForm::class);
        $special->handles(Argument::type('array'))->willReturn(true);
        $special->evaluate(
            Argument::type('array'),
            Argument::type(Env::class),
            Argument::type(Evaluator::class))->shouldBeCalled();
        $exp = [new Symbol('asdf')];
        $subj = new Evaluator($special->reveal());
        $subj($exp, $this->env->reveal());
    }
}