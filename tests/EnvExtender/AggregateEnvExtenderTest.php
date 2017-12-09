<?php

namespace EnvExtender;

use Sphpeme\Env\AggregateEnv;
use Sphpeme\Env\EnvInterface;
use Sphpeme\EnvExtender\AggregateEnvExtender;
use PHPUnit\Framework\TestCase;

class AggregateEnvExtenderTest extends TestCase
{

    public function test__invoke()
    {
        $subj = new AggregateEnvExtender();
        $env = $subj->__invoke($this->prophesize(EnvInterface::class)->reveal());
        static::assertInstanceOf(AggregateEnv::class, $env);
    }
}
