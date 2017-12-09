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
                return $args[0];
            });
        $this->subj = new LambdaExpHandler($envExtender->reveal());

        $lambda = Symbol::make('lambda');

        $symbol =  $this->prophesize(Symbol::class);
        $symbol->__toString()->willReturn('+');
        $this->exp = [$lambda, [], [$symbol->reveal(), 1, 2, 3]];
        $this->eval = $this->prophesize(Evaluator::class);
    }

    public function testHandlesLambda()
    {
        static::assertTrue($this->subj->handles($this->exp));
    }

    public function testLambda()
    {
        $this->env->{'+'} = function (...$args) {
            static::assertEquals([1,2,3], $args);
        };

        $lambda = $this->subj->evaluate($this->exp, $this->env->reveal(), $this->eval->reveal());

        static::assertInstanceOf(\Closure::class, $lambda);
        $lambda();
    }

}