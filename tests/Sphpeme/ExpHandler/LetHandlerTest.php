<?php

use Prophecy\Argument as Arg;
use Prophecy\Prophecy\ObjectProphecy;
use PHPUnit\Framework\TestCase;
use Sphpeme\ExpHandler\LetHandler;
use Sphpeme\Symbol;

class LetHandlerTest extends TestCase
{
    /** @var LetHandler */
    private $subj;

    /** @var ObjectProphecy|\Sphpeme\Env\EnvInterface */
    private $env;

    /** @var ObjectProphecy|\Sphpeme\Evaluator */
    private $eval;

    protected function setUp()
    {
        $this->subj = new LetHandler();
        $this->env = $this->prophesize(\Sphpeme\Env\EnvInterface::class);
        $this->eval = $this->prophesize(Sphpeme\Evaluator::class);
    }

    public function testEvaluate()
    {
        /** @var \Sphpeme\Env\EnvInterface $revealedEnv */
        $revealedEnv = $this->env->reveal();

        $letMock = $this->prophesize(Symbol::class);
        $xMock = $this->prophesize(Symbol::class);
        $tenMock = $this->prophesize(\Sphpeme\Scalar::class);
        $displayMock = $this->prophesize(Symbol::class);
        $yMock = $this->prophesize(Symbol::class);
        $twoMock = $this->prophesize(\Sphpeme\Scalar::class);

        $program = [
            $letMock->reveal(),
            [
                [$xMock->reveal(), $tenMock->reveal()],
                [$yMock->reveal(), $twoMock->reveal()]
            ],
            [$displayMock->reveal(), $xMock->reveal()],
            [$displayMock->reveal(), $yMock->reveal()]
        ];


        $this->eval->__invoke(
            Arg::type('array'), Arg::type(\Sphpeme\Env\EnvInterface::class))
            ->should(function ($args) {
                list($exp, $env) = $args[0]->getArguments();

                list(
                    list($lambda, list($x, $y), $displayX, $displayY),
                $ten, $two) = $exp;
                LetHandlerTest::assertInstanceOf(\Sphpeme\Env\EnvInterface::class, $env);
                LetHandlerTest::assertEquals(Symbol::make('lambda'), $lambda);
                LetHandlerTest::assertInstanceOf(Symbol::class, $x);
                LetHandlerTest::assertInstanceOf(Symbol::class, $y);

                LetHandlerTest::assertInstanceOf(\Sphpeme\Scalar::class, $ten);
                LetHandlerTest::assertInstanceOf(\Sphpeme\Scalar::class, $two);

                LetHandlerTest::assertInstanceOf(Symbol::class, $displayX[0]);
                LetHandlerTest::assertInstanceOf(Symbol::class, $displayY[0]);

                LetHandlerTest::assertInstanceOf(Symbol::class, $displayX[1]);
                LetHandlerTest::assertInstanceOf(Symbol::class, $displayY[1]);
            });

        $this->subj->evaluate($program, $revealedEnv, $this->eval->reveal());

    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles([Symbol::make('let')]));
    }

}
