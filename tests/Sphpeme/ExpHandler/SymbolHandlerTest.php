<?php

use PHPUnit\Framework\TestCase;
use Sphpeme\Env;
use Sphpeme\Symbol;
use Sphpeme\ExpHandler\SymbolHandler;

class SymbolHandlerTest extends TestCase
{
    /** @var SymbolHandler */
    private $subj;

    /** @var \Prophecy\Prophecy\ObjectProphecy|Symbol */
    private $exp;

    /** @var Env */
    private $env;

    public function setUp()
    {
        $this->subj = new SymbolHandler();
        $this->exp = $this->prophesize(Symbol::class);
        $this->exp->__toString()->willReturn('hello');
        $this->env = new Env;
        $this->env->hello = true;
    }


    public function testEvaluate()
    {
        static::assertTrue($this->subj->evaluate($this->exp->reveal(), $this->env, new \Sphpeme\Evaluator()));

    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles($this->exp->reveal()));
    }
}
