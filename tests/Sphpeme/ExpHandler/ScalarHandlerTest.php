<?php

use Prophecy\Prophecy\ObjectProphecy;
use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\Scalar;
use PHPUnit\Framework\TestCase;

class ScalarHandlerTest extends TestCase
{
    /** @var ScalarHandler */
    private $subj;

    /** @var ObjectProphecy|\Sphpeme\Env\EnvInterface */
    private $env;

    /** @var \Sphpeme\Evaluator */
    private $eval;

    protected function setUp()
    {
        $this->subj = new ScalarHandler();
        $this->env = $this->prophesize(\Sphpeme\Env\EnvInterface::class);
        $this->eval = new \Sphpeme\Evaluator();
    }

    public function testEvaluate()
    {
        $revealedEnv = $this->env->reveal();
        static::assertEquals('string', $this->subj->evaluate(new Scalar('string'), $revealedEnv, $this->eval));
        static::assertTrue($this->subj->evaluate(new Scalar(true), $revealedEnv, $this->eval));
        static::assertEquals(10, $this->subj->evaluate(new Scalar(10), $revealedEnv, $this->eval));
        static::assertEquals(1.1, $this->subj->evaluate(new Scalar(1.1), $revealedEnv, $this->eval));
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles(new Scalar('string')));
        static::assertTrue($this->subj->handles(new Scalar(10)));
        static::assertTrue($this->subj->handles(new Scalar(1.1)));
        static::assertTrue($this->subj->handles(new Scalar(true)));
    }

}
