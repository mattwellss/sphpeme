<?php

namespace tests\Sphpeme\Evaluator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sphpeme\DefineSpecialForm;
use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class DefineSpecialFormTest extends TestCase
{
    /** @var DefineSpecialForm */
    private $subj;
    private $env;
    private $exp;
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(Env::class);
        $this->subj = new DefineSpecialForm();
        $this->exp = [new Symbol('define'), new Symbol('x'), 10];
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