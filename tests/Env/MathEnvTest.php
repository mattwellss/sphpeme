<?php
declare(strict_types=1);

namespace tests\Sphpeme\Env;

use PHPUnit\Framework\TestCase;
use Sphpeme\Env\MathEnv;
use VladaHejda;

class MathEnvTest extends TestCase
{
    use VladaHejda\AssertException;

    /** @var MathEnv */
    private $subj;
    protected function setUp()
    {
        $this->subj = new MathEnv;
    }

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        // compatibility with AssertException, which expects phpunit pre-6.0
        if (!class_exists(\PHPUnit_Framework_TestCase::class)) {
            class_alias(static::class, \PHPUnit_Framework_TestCase::class);
        }
        parent::__construct($name, $data, $dataName);
    }

    public function testAdd()
    {
        static::assertEquals(6, $this->subj->add(1, 2, 3));
        static::assertEquals(0, $this->subj->add(0));
        static::assertEquals([$this->subj, 'add'], $this->subj->__get('+'));
        static::assertException(
            function () { $this->subj->add(1, '2'); });
    }

    public function testSubtract()
    {
        static::assertEquals(0, $this->subj->subtract(3, 2, 1));
        static::assertEquals(0, $this->subj->subtract(0));
        static::assertEquals([$this->subj, 'subtract'], $this->subj->__get('-'));
        static::assertException(
            function () { $this->subj->subtract(1, '2'); });
    }

    public function testMultiply()
    {
        static::assertEquals(6, $this->subj->multiply(3, 2, 1));
        static::assertEquals(0, $this->subj->multiply(0));
        static::assertEquals([$this->subj, 'multiply'], $this->subj->__get('*'));
        static::assertException(
            function () { $this->subj->multiply(1, '2'); });
    }

    public function testDivide()
    {
        static::assertEquals(2.5, $this->subj->divide(10, 2, 2));
        static::assertEquals(1, $this->subj->divide(1));
        static::assertEquals([$this->subj, 'divide'], $this->subj->__get('/'));
        static::assertException(
            function () { $this->subj->divide(1, '2'); });

    }

    public function testPi()
    {
        static::assertEquals(M_PI, $this->subj->pi);
    }

    public function testGreaterThan()
    {
        static::assertTrue($this->subj->isGreaterThan(1, 0));
        static::assertEquals([$this->subj, 'isGreaterThan'], $this->subj->__get('>'));
        static::assertException(
            function () { $this->subj->isGreaterThan(1, '2'); });
    }

    public function testSmallerThan()
    {
        static::assertTrue($this->subj->isSmallerThan(0, 1));
        static::assertEquals([$this->subj, 'isSmallerThan'], $this->subj->__get('<'));
        static::assertException(
            function () { $this->subj->isSmallerThan(1, '2'); });
    }

    public function testGreaterThanOrEqual()
    {
        static::assertTrue($this->subj->isGreaterThanOrEqual(1, 1));
        static::assertTrue($this->subj->isGreaterThanOrEqual(2, 1));
        static::assertEquals([$this->subj, 'isGreaterThanOrEqual'], $this->subj->__get('>='));
        static::assertException(
            function () { $this->subj->isGreaterThanOrEqual(1, '2'); });
    }

    public function testSmallerThanOrEqual()
    {
        static::assertTrue($this->subj->isSmallerThanOrEqual(1, 1));
        static::assertTrue($this->subj->isSmallerThanOrEqual(2, 3));
        static::assertEquals([$this->subj, 'isSmallerThanOrEqual'], $this->subj->__get('<='));
        static::assertException(
            function () { $this->subj->isSmallerThanOrEqual(1, '2'); });
    }

    public function testIsEqual()
    {
        static::assertTrue($this->subj->isEqual(1, 1, 1.0));
        static::assertEquals([$this->subj, 'isEqual'], $this->subj->__get('='));
        static::assertException(
            function () { $this->subj->isEqual(1, '2'); });
    }

    public function testIsNumber()
    {
        static::assertTrue($this->subj->isNumber(1));
        static::assertTrue($this->subj->isNumber(10.3));
        static::assertFalse($this->subj->isNumber('1000'));
        static::assertEquals([$this->subj, 'isNumber'], $this->subj->__get('number?'));
    }

    public function testIsInteger()
    {
        static::assertTrue($this->subj->isInteger(1));
        static::assertFalse($this->subj->isInteger(10.3));
        static::assertTrue($this->subj->isInteger(1.0));
        static::assertEquals([$this->subj, 'isInteger'], $this->subj->__get('integer?'));
    }

    public function testIsComplex()
    {
        static::assertThrowable(function () {
            $this->subj->isComplex('2+3i');
        }, \Throwable::class, null, 'Not implemented');
        static::assertEquals([$this->subj, 'isComplex'], $this->subj->__get('complex?'));
    }

    public function testIsReal()
    {
        static::assertThrowable(function () {
            $this->subj->isComplex(1);
        }, \Throwable::class, null, 'Not implemented');
        static::assertEquals([$this->subj, 'isReal'], $this->subj->__get('real?'));
    }

    public function testIsExact()
    {
        static::assertTrue($this->subj->isExact(1));
        static::assertFalse($this->subj->isExact(2.2));
        static::assertEquals([$this->subj, 'isExact'], $this->subj->__get('exact?'));
    }

    public function testIsInexact()
    {
        static::assertFalse($this->subj->isInexact(1));
        static::assertTrue($this->subj->isInexact(2.2));
        static::assertEquals([$this->subj, 'isInexact'], $this->subj->__get('inexact?'));
    }

    public function testIsZero()
    {
        static::assertTrue($this->subj->isZero(0));
        static::assertException(
            function () { $this->subj->isZero('0'); }, \InvalidArgumentException::class);
    }

    public function testIsPositive()
    {
        static::assertTrue($this->subj->isPositive(1));
        static::assertFalse($this->subj->isPositive(-1));
        static::assertEquals([$this->subj, 'isPositive'], $this->subj->__get('positive?'));
        static::assertException(
            function () { $this->subj->isPositive('1'); }, \InvalidArgumentException::class);
    }

    public function testIsNegative()
    {
        static::assertFalse($this->subj->isNegative(1));
        static::assertTrue($this->subj->isNegative(-1));
        static::assertEquals([$this->subj, 'isNegative'], $this->subj->__get('negative?'));
        static::assertException(
            function () { $this->subj->isNegative('-1'); }, \InvalidArgumentException::class);
    }

    public function testIsOdd()
    {
        static::assertFalse($this->subj->isOdd(2));
        static::assertFalse($this->subj->isOdd(0));
        static::assertTrue($this->subj->isOdd(1));
        static::assertEquals([$this->subj, 'isOdd'], $this->subj->__get('odd?'));
        static::assertException(
            function () { $this->subj->isOdd('1'); }, \InvalidArgumentException::class);
    }

    public function testIsEven()
    {
        static::assertTrue($this->subj->isEven(2));
        static::assertTrue($this->subj->isEven(0));
        static::assertFalse($this->subj->isEven(1));
        static::assertEquals([$this->subj, 'isEven'], $this->subj->__get('even?'));
        static::assertException(
            function () { $this->subj->isEven('2'); }, \InvalidArgumentException::class);
    }

    public function testMax()
    {
        static::assertEquals(3, $this->subj->max(0, 1, 2, 3));
        static::assertException(function () {
            $this->subj->max('a', 's', 'd', 'f');
        }, \InvalidArgumentException::class);
    }

    public function testMin()
    {
        static::assertEquals(0, $this->subj->min(3, 2, 1, 0));
        static::assertException(function () {
            $this->subj->min('a', 's', 'd', 'f');
        }, \InvalidArgumentException::class);
    }

    public function testAbs()
    {
        static::assertEquals(1, $this->subj->abs(1));
        static::assertEquals(1, $this->subj->abs(-1));
    }
}
