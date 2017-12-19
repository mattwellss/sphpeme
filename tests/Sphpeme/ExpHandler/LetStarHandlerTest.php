<?php

namespace ExpHandler;

use Prophecy\Argument as Arg;
use Sphpeme\Env\EnvInterface;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\LetStarHandler;
use PHPUnit\Framework\TestCase;
use Sphpeme\Scalar;
use Sphpeme\Symbol;

class LetStarHandlerTest extends TestCase
{
    /** @var LetStarHandler */
    private $subj;
    private $env;
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(EnvInterface::class);
        $this->subj = new LetStarHandler();
        $this->eval = $this->prophesize(Evaluator::class);
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles([Symbol::make('let*')]));
    }


    public function testEvalute()
    {
        /** @var EnvInterface $revealedEnv */
        $revealedEnv = $this->env->reveal();

        $letStar = Symbol::make('let*');
        $x = Symbol::make('x');
        $ten = new Scalar(10);
        $display = Symbol::make('display');
        $y = Symbol::make('y');

        $program = [
            $letStar,
            [
                [$x, $ten],
                [$y, $x]
            ],
            [$display, $x],
            [$display, $y]
        ];

        /**
         * @param \Prophecy\Call\Call[] $args
         */
        $assertionCb = function ($args) {
            list($exp, $env) = $args[0]->getArguments();

            list(
                $let1, list($bindings1),
                list($let2, list($bindings2), $display1, $display2)) = $exp;

            LetStarHandlerTest::assertEquals(Symbol::make('let'), $let1);
            LetStarHandlerTest::assertEquals(Symbol::make('x'), $bindings1[0]);
            LetStarHandlerTest::assertEquals(new Scalar(10), $bindings1[1]);

            LetStarHandlerTest::assertEquals(Symbol::make('let'), $let2);
            LetStarHandlerTest::assertEquals(Symbol::make('y'), $bindings2[0]);
            LetStarHandlerTest::assertEquals(Symbol::make('x'), $bindings2[1]);

            LetStarHandlerTest::assertEquals(Symbol::make('display'), $display1[0]);
            LetStarHandlerTest::assertEquals(Symbol::make('x'), $display1[1]);

            LetStarHandlerTest::assertEquals(Symbol::make('display'), $display2[0]);
            LetStarHandlerTest::assertEquals(Symbol::make('y'), $display2[1]);
        };

        $this->eval->__invoke(
            Arg::type('array'), Arg::type(EnvInterface::class))
            ->should($assertionCb);

        $this->subj->evaluate($program, $revealedEnv, $this->eval->reveal());
    }}
