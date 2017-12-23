<?php
/**
 * User: mpw
 * Date: 2017-12-19 22:12 PM
 */

namespace ExpHandler;

use Prophecy\Argument as Arg;
use Sphpeme\Env\EnvInterface;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\LetHandler;
use Sphpeme\ExpHandler\NamedLetHandler;
use PHPUnit\Framework\TestCase;
use Sphpeme\Scalar;
use Sphpeme\Symbol;

class NamedLetHandlerTest extends TestCase
{

    /** @var NamedLetHandler */
    private $subj;

    private $program;

    private $env;

    private $eval;

    protected function setUp()
    {
        $this->subj = new NamedLetHandler(new LetHandler());
        $this->env = $this->prophesize(EnvInterface::class);
        $this->eval = $this->prophesize(Evaluator::class);
        $this->program = [
            Symbol::make('let'),
            Symbol::make('foo'),
            [[Symbol::make('x'), new Scalar(1)]],
            Symbol::make('x')
        ];
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles($this->program));
    }

    public function testEvaluate()
    {
        /**
         * @param \Prophecy\Call\Call[] $args
         */
        $defineAssertionCb = function ($args) {
            list($exp, $env) = $args[0]->getArguments();

            list($define, $name, $lambdaExp) = $exp;
            list($lambda, $args) = $lambdaExp;
            $lambdaBody = \array_slice($lambdaExp, 2);

            NamedLetHandlerTest::assertEquals(Symbol::make('define'), $define);
            NamedLetHandlerTest::assertEquals(Symbol::make('foo'), $name);
            NamedLetHandlerTest::assertEquals(Symbol::make('lambda'), $lambda);
            NamedLetHandlerTest::assertEquals(Symbol::make('x'), $args[0]);
            NamedLetHandlerTest::assertEquals(Symbol::make('x'), $lambdaBody[0]);
        };

        $this->eval->__invoke(
            Arg::type('array'), Arg::type(EnvInterface::class))
            ->should($defineAssertionCb);

        $this->eval->__invoke([Symbol::make('foo'), new Scalar(1)], Arg::type(EnvInterface::class))
            ->shouldBeCalled();

        $this->subj->evaluate($this->program, $this->env->reveal(), $this->eval->reveal());
    }
}
