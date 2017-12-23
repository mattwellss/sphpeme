<?php
/**
 * User: mpw
 * Date: 2017-12-23 13:30 PM
 */

namespace Env;

use Sphpeme\Env\EnvInterface;
use Sphpeme\Env\MappedEnvTrait;
use PHPUnit\Framework\TestCase;

class MappendEnvTraitTest extends TestCase
{
    private $subj;

    protected function setUp()
    {
        $this->subj = new class implements EnvInterface {
            use MappedEnvTrait;

            const MAPPING = [
                'foo?' => 'foo',
                'blah-blah' => 'blahBlah'
            ];

            public $foo = 'bar!!!';
            public function blahBlah()
            {

            }
        };
    }

    public function testIsset()
    {
        static::assertTrue(isset($this->subj->{'foo?'}));
        static::assertTrue(isset($this->subj->{'blah-blah'}));
    }

    public function testGet()
    {
        static::assertEquals('bar!!!', $this->subj->{'foo?'});
        static::assertInternalType('callable', $this->subj->{'blah-blah'});
    }
}
