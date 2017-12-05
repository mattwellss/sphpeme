<?php
/**
 * User: mpw
 * Date: 2017-12-03 14:58 PM
 */

use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\Scalar;
use PHPUnit\Framework\TestCase;

class ScalarHandlerTest extends TestCase
{
    /** @var ScalarHandler */
    private $subj;

    /** @var \Sphpeme\Env */
    private $env;

    /** @var \Sphpeme\Evaluator */
    private $eval;

    protected function setUp()
    {
        $this->subj = new ScalarHandler();
        $this->env = new \Sphpeme\Env();
        $this->eval = new \Sphpeme\Evaluator();
    }

    public function testEvaluate()
    {
        static::assertEquals('string', $this->subj->evaluate(new Scalar('string'), $this->env, $this->eval));
        static::assertTrue($this->subj->evaluate(new Scalar(true), $this->env, $this->eval));
        static::assertEquals(10, $this->subj->evaluate(new Scalar(10), $this->env, $this->eval));
        static::assertEquals(1.1, $this->subj->evaluate(new Scalar(1.1), $this->env, $this->eval));
    }

    public function testHandles()
    {
        static::assertTrue($this->subj->handles(new Scalar('string')));
        static::assertTrue($this->subj->handles(new Scalar(10)));
        static::assertTrue($this->subj->handles(new Scalar(1.1)));
        static::assertTrue($this->subj->handles(new Scalar(true)));
    }

}