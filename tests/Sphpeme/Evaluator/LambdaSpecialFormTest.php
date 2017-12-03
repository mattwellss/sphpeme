<?php

namespace tests\Sphpeme\Evaluator;


use PHPUnit\Framework\TestCase;
use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\LambdaSpecialForm;
use Sphpeme\Symbol;

class LambdaSpecialFormTest extends TestCase
{
    /** @var LambdaSpecialForm */
    private $subj;
    private $env;
    private $exp;
    private $eval;

    protected function setUp()
    {
        $this->env = $this->prophesize(Env::class);
        $this->subj = new LambdaSpecialForm();
        $this->exp = [new Symbol('lambda'), [], [new Symbol('+'), 1, 2, 3]];
        $this->eval = $this->prophesize(Evaluator::class);
    }

    public function testHandlesLambda()
    {
        static::assertTrue($this->subj->handles($this->exp));
    }

    public function testLambda()
    {
        $this->env->{'+'} = function (...$args) {
            static::assertEquals([1,2,3], $args);
        };

        $lambda = $this->subj->evaluate($this->exp, $this->env->reveal(), $this->eval->reveal());

        static::assertInstanceOf(\Closure::class, $lambda);
        $lambda();
    }

}