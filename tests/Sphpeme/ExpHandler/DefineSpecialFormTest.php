<?php

namespace tests\Sphpeme\Evaluator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\DefineExpHandler;
use Sphpeme\Symbol;

class DefineSpecialFormTest extends TestCase
{
    /** @var DefineExpHandler */
    private $subj;
    private $env;
    private $exp;
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(Env::class);
        $this->subj = new DefineExpHandler();

        $define = $this->prophesize(Symbol::class);
        $define->__toString()->willReturn('define');
        $x = $this->prophesize(Symbol::class);
        $x->__toString()->willReturn('x');

        $this->exp = [$define->reveal(), $x->reveal(), 10];

        $this->eval = $this->prophesize(Evaluator::class);
        $this->eval->__invoke(10, Argument::type(Env::class))->willReturn(10);
    }

    public function testHandlesLambda()
    {
        static::assertTrue($this->subj->handles($this->exp));
    }

    public function testDefine()
    {
        $this->subj->evaluate($this->exp, $this->env->reveal(), $this->eval->reveal());

        static::assertEquals(10, $this->env->x);
    }
}