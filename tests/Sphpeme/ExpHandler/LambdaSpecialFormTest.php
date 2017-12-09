<?php

namespace tests\Sphpeme\Evaluator;


use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sphpeme\Env;
use Sphpeme\EnvExtender;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\LambdaExpHandler;
use Sphpeme\Symbol;

class LambdaSpecialFormTest extends TestCase
{
    /** @var LambdaExpHandler */
    private $subj;
    private $env;
    private $exp;
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(Env\EnvInterface::class);
        $envExtender = $this->prophesize(EnvExtender::class);
        $envExtender
            ->__invoke(Argument::type(Env\EnvInterface::class), Argument::type(Env\EnvInterface::class))
            ->will(function ($args) {
                return $args[1];
            });
        $this->subj = new LambdaExpHandler($envExtender->reveal());

        $lambda = Symbol::make('lambda');

        $plus =  $this->prophesize(Symbol::class);
        $plus->__toString()->willReturn('+');

        $sub =  $this->prophesize(Symbol::class);
        $sub->__toString()->willReturn('-');

        $this->exp = [$lambda, [], [$plus->reveal(), 1, 2, 3], [$sub->reveal(), 3, 3, 3]];
        $this->eval = $this->prophesize(Evaluator::class);
    }

    public function testHandlesLambda()
    {
        static::assertTrue($this->subj->handles($this->exp));
    }

    public function testEnvExtension()
    {
        $arg = $this->prophesize(Symbol::class);
        $arg->__toString()
            ->shouldBeCalled()
            ->willReturn('asdf');
        $this->exp[1] = [$arg->reveal()];
        $lambda = $this->subj->evaluate($this->exp, $this->env->reveal(), $this->eval->reveal());
        $lambda('asdf');
    }

    public function testLambda()
    {
        $this->eval
            ->__invoke(Argument::type('array'), Argument::type(Env\EnvInterface::class))
            ->shouldBeCalledTimes(2)
            ->willReturn('first', 'second'); // the second invocation is returned

        $lambda = $this->subj->evaluate($this->exp, $this->env->reveal(), $this->eval->reveal());

        static::assertInstanceOf(\Closure::class, $lambda);
        static::assertEquals('second', $lambda());
    }

}