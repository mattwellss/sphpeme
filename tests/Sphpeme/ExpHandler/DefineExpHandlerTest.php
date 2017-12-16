<?php

namespace tests\Sphpeme\Evaluator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Call\Call;
use Prophecy\Prophecy\ObjectProphecy;
use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\DefineExpHandler;
use Sphpeme\Symbol;

class DefineExpHandlerTest extends TestCase
{
    /** @var DefineExpHandler */
    private $subj;
    /** @var ObjectProphecy|Env\EnvInterface */
    private $env;
    private $exp;
    /** @var ObjectProphecy|Evaluator */
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(Env\EnvInterface::class);
        $this->subj = new DefineExpHandler();

        $define = Symbol::make('define');
        $x = $this->prophesize(Symbol::class);
        $x->__toString()->willReturn('x');

        $this->exp = [$define, $x->reveal(), 10];

        $this->eval = $this->prophesize(Evaluator::class);
    }

    public function testHandlesDefine()
    {
        static::assertTrue($this->subj->handles($this->exp));
    }

    public function testDefine()
    {
        $this->eval->__invoke(10, Argument::type(Env\EnvInterface::class))
            ->shouldBeCalled()
            ->willReturn(10);
        $this->subj->evaluate($this->exp, $this->env->reveal(), $this->eval->reveal());
    }

    public function testDefineFnWithoutLambda()
    {
        $define = $this->prophesize(Symbol::class);
        $define->__toString()->willReturn('define');

        $name = $this->prophesize(Symbol::class);
        $name->__toString()->willReturn('name');

        $x = $this->prophesize(Symbol::class);
        $x->__toString()->willReturn('x');

        $exp = [
            $define->reveal(),
            [
                $name->reveal(),
                $x->reveal(),
            ],
            $x->reveal()
        ];

        /**
         * @param Call[] $calls
         */
        $assertionCb = function (array $calls) {
            list($exp, $env) = $calls[0]->getArguments();
            DefineExpHandlerTest::assertEquals('lambda', $exp[0]);
            DefineExpHandlerTest::assertEquals('x', $exp[1][0]);
            DefineExpHandlerTest::assertEquals('x', $exp[2]);
        };

        $this->eval->__invoke(Argument::cetera())
            ->should($assertionCb) ;

        $this->subj->evaluate($exp, $this->env->reveal(), $this->eval->reveal());

    }
}