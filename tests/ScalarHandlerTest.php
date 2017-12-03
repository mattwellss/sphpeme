<?php
/**
 * User: mpw
 * Date: 2017-12-03 14:58 PM
 */

use Sphpeme\ScalarHandler;
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

    public function testEvaluateString()
    {
        static::assertEquals('string', $this->subj->evaluate('string', $this->env, $this->eval));
    }

    public function testHandlesString()
    {
        static::assertTrue($this->subj->handles('string'));
    }

    public function testEvaluateInt()
    {
        static::assertEquals(10, $this->subj->evaluate(10, $this->env, $this->eval));
    }

    public function testHandlesInt()
    {
        static::assertTrue($this->subj->handles(10));
    }

    public function testEvaluateFloat()
    {
        static::assertEquals(1.1, $this->subj->evaluate(1.1, $this->env, $this->eval));
    }

    public function testHandlesFloat()
    {
        static::assertTrue($this->subj->handles(1.1));
    }
}
