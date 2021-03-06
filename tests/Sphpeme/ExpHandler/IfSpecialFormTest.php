<?php

namespace tests\Sphpeme\Evaluator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\ExpHandler\IfExpHandler;
use Sphpeme\Symbol;

class IfSpecialFormTest extends TestCase
{
    /** @var IfExpHandler */
    private $subj;
    private $env;
    private $exp;
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(Env\EnvInterface::class);
        $this->subj = new IfExpHandler();
        $if = Symbol::make('if');

        $this->exp = [$if, 1 , 42, 10];
        $this->eval = $this->prophesize(Evaluator::class);
        $this->eval->__invoke(1, Argument::type(Env\EnvInterface::class))->willReturn(1);
        $this->eval->__invoke(42, Argument::type(Env\EnvInterface::class))->willReturn(42);
    }
    public function testEvaluate()
    {
        static::assertEquals(42, ($this->subj)->evaluate(['if', 1, 42, 0], $this->env->reveal(), $this->eval->reveal()));
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles($this->exp));
    }
}
