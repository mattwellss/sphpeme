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

    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $env;

    public function setUp()
    {
        $this->subj = new SymbolHandler();
        $this->exp = $this->prophesize(Symbol::class);
        $this->exp->__toString()->willReturn('hello');
        $this->env = $this->prophesize(Env\EnvInterface::class);
    }


    public function testEvaluate()
    {
        $this->env
            ->__call('__get', ['hello'])
            ->shouldBeCalled();
        $this->subj->evaluate($this->exp->reveal(), $this->env->reveal(), new \Sphpeme\Evaluator());
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles($this->exp->reveal()));
    }
}
