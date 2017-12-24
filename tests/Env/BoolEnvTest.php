<?php

namespace tests\Sphpeme\Env;

use PHPUnit\Framework\TestCase;
use Sphpeme\Env\BoolEnv;

class BoolEnvTest extends TestCase
{

    protected function setUp()
    {
        $this->subj = new BoolEnv;
    }

    public function testNot()
    {
        self::assertTrue($this->subj->not(false));
    }

    public function testIsBool()
    {
        self::assertTrue($this->subj->isBool(false));
        self::assertTrue($this->subj->isBool(true));
    }

    public function testEq()
    {
        static::assertTrue($this->subj->isEq(true, true));
        static::assertFalse($this->subj->isEq(new \stdClass, new \stdClass));
    }

    public function testEqv()
    {
        static::assertTrue($this->subj->isEqv(false, false));
        static::assertTrue($this->subj->isEqv(new \stdClass, new \stdClass));
    }

    public function testEqual()
    {
        static::assertTrue($this->subj->isEqual(false, false));
        static::assertTrue($this->subj->isEqual(new \stdClass, new \stdClass));
    }

}
