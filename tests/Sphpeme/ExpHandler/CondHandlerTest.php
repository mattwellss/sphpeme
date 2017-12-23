<?php

namespace ExpHandler;

use Prophecy\Argument;
use Prophecy\Call\Call;
use Sphpeme\Env\EnvInterface;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\CondHandler;
use PHPUnit\Framework\TestCase;
use Sphpeme\Scalar;
use Sphpeme\Symbol;

class CondHandlerTest extends TestCase
{

    /** @var CondHandler */
    private $subj;

    protected function setUp()
    {
        $this->subj = new CondHandler();
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles([Symbol::make('cond')]));
    }

    public function testEvaluate()
    {
        /*
         * (cond
         *   ((> x 1) x)
         *   ((< x 0) (display x))
         *   (else (+ 10 x))
         */
        $program = [
            Symbol::make('cond'),
            [
                [
                    Symbol::make('>'),
                    Symbol::make('x'),
                    new Scalar(1)
                ],
                Symbol::make('x')
            ],
            [
                [
                    Symbol::make('<'),
                    Symbol::make('x'),
                    new Scalar(0)
                ],
                [
                    Symbol::make('display'),
                    Symbol::make('x')
                ]
            ],
            [
                Symbol::make('else'),
                [
                    Symbol::make('+'),
                    new Scalar(10),
                    Symbol::make('x')
                ]
            ]
        ];

        $env = $this->prophesize(EnvInterface::class);
        /** @var Evaluator $eval */
        $eval = $this->prophesize(Evaluator::class);

        /**
         * @param Call[] $calls
         */
        $assertionCb = function ($calls) {
            list($exp, $env) = $calls[0]->getArguments();

            /*
             * (if (> x 1) x
             *     (if (< x 0) (display x)
             *         (+ 10 x)))
             */
            list($if, $cond1, $consec1,
                list($if2, $cond2, $consec2, $else)) = $exp;

            CondHandlerTest::assertEquals(Symbol::make('if'), $if);
            CondHandlerTest::assertEquals([Symbol::make('>'), Symbol::make('x'), new Scalar(1)], $cond1);
            CondHandlerTest::assertEquals(Symbol::make('x'), $consec1);

            CondHandlerTest::assertEquals([Symbol::make('<'), Symbol::make('x'), new Scalar(0)], $cond2);
            CondHandlerTest::assertEquals([Symbol::make('display'), Symbol::make('x')], $consec2);

            CondHandlerTest::assertEquals([Symbol::make('+'), new Scalar(10), Symbol::make('x')], $else);
        };
        $eval->__invoke(Argument::type('array'), $env->reveal())
            ->should($assertionCb);

        $this->subj->evaluate($program, $env->reveal(), $eval->reveal());
    }
}
