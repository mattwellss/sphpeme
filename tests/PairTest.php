<?php
/**
 * User: mpw
 * Date: 2017-12-16 12:57 PM
 */

namespace tests\Sphpeme;

use Sphpeme\Pair;
use PHPUnit\Framework\TestCase;

class PairTest extends TestCase
{

    public function testList()
    {
        static::assertInstanceOf(Pair::class, Pair::list(4));
        static::assertInstanceOf(Pair::class, Pair::list(4, 5, 6, 7));
    }

    public function testCar()
    {
        $subj = Pair::list(4);
        static::assertEquals(4, $subj->car());
    }

    public function testCdr()
    {
        $subj = Pair::list(4, 5);
        static::assertInstanceOf(Pair::class, $subj->cdr());
    }

    public function testCons()
    {
        $subj = Pair::list(4);
        static::assertEquals(5, Pair::cons(5, $subj)->car());
    }

    public function testToArray()
    {
        $onesym = \Sphpeme\Symbol::make(1);
        $subj = Pair::list($onesym, 2, 3, Pair::list(4, 5));
        static::assertEquals([$onesym, 2, 3, [4, 5]], $subj->toArray());
    }
}
