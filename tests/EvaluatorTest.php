<?php

namespace tests\Sphpeme;

use Prophecy\Argument;
use Sphpeme\Env;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\ExpHandler;
use Sphpeme\Scalar;
use Sphpeme\Symbol;

class EvaluatorTest extends TestCase
{
    /** @var ObjectProphecy */
    private $env;
    /** @var Evaluator */
    private $subj;

    public function setUp()
    {
        $this->env = $this->prophesize(Env\EnvInterface::class);
        $this->subj = new Evaluator;
    }

    public function testBasicEnvCall()
    {
        $this->env->{'+'} = function (...$args) {
            static::assertEquals([1, 2, 3], $args);
        };

        $plus = $this->prophesize(Symbol::class);
        $plus->__toString()
            ->willReturn('+');

        $one = $this->prophesize(Scalar::class);
        $one->getValue()
            ->willReturn(1);

        $two = $this->prophesize(Scalar::class);
        $two->getValue()
            ->willReturn(2);

        $three = $this->prophesize(Scalar::class);
        $three->getValue()
            ->willReturn(3);

        $this->subj->__invoke([$plus->reveal(), $one->reveal(), $two->reveal(), $three->reveal(),],
            $this->env->reveal());
    }

    public function testSpecialForms()
    {
        $special = $this->prophesize(ExpHandler::class);
        $special->handles(Argument::type('array'))->willReturn(true);
        $special->evaluate(
            Argument::type('array'),
            Argument::type(Env\EnvInterface::class),
            Argument::type(Evaluator::class))->shouldBeCalled();

        $symbol = $this->prophesize(Symbol::class);

        $exp = [$symbol->reveal()];

        $subj = new Evaluator($special->reveal());
        $subj($exp, $this->env->reveal());
    }
}