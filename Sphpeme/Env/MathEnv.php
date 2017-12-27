<?php
declare(strict_types=1);

namespace Sphpeme\Env;

/**
 * @SuppressWarnings(PHPMD.ShortVariableName)
 */
class MathEnv implements EnvInterface
{
    use MappedEnvTrait;

    const INVALID_ARG_REQUIRES_NUMERIC = 'All arguments must be numeric';
    const INVALID_ARG_TYPE_PRED_MATCH = 'Supplied arguments do not match the first';

    const MAPPING = [
        '+' => 'add',
        '-' => 'subtract',
        '*' => 'multiply',
        '/' => 'divide',
        '>' => 'isGreaterThan',
        '>=' => 'isGreaterThanOrEqual',
        '<' => 'isSmallerThan',
        '<=' => 'isSmallerThanOrEqual',
        '=' => 'isEqual',
        'number?' => 'isNumber',
        'integer?' => 'isInteger',
        'real?' => 'isReal',
        'complex?' => 'isComplex',
        'exact?' => 'isExact',
        'inexact?' => 'isInexact',
        'positive?' => 'isPositive',
        'negative?' => 'isNegative',
        'odd?' => 'isOdd',
        'even?' => 'isEven',
    ];

    /** @var float */
    public $pi = M_PI;

    /**
     * Sum the args
     *
     * @param int[]|float[] $args
     * @return int|float
     * @throws \InvalidArgumentException
     */
    public function add(...$args)
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);
        return array_sum($args);
    }

    /**
     * Subtract the args
     *
     * @param int[]|float[] $args
     * @return int|float
     * @throws \InvalidArgumentException
     */
    public function subtract(...$args)
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);

        $arg = $args[0];
        $args = \array_slice($args, 1);

        return $arg - array_sum($args);
    }

    /**
     * Multiply the args
     *
     * @param int[]|float[] $args
     * @return int|float
     * @throws \InvalidArgumentException
     */
    public function multiply(...$args)
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);
        return array_product($args);
    }

    /**
     * Divide the args
     *
     * @param int[]|float[] $args
     * @return int|float
     * @throws \InvalidArgumentException
     */
    public function divide(...$args)
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);

        $arg = $args[0];
        $args = \array_slice($args, 1);

        return $arg / array_product($args);
    }

    /**
     * Returns true if $a is greater than $b, otherwise false
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param int|float $a
     * @param int|float $b
     * @return bool
     */
    public function isGreaterThan($a, $b): bool
    {
        array_reduce([$a, $b], [$this, 'enforcePredicate'], [$this, 'isNumber']);
        return $a > $b;
    }

    /**
     * Returns true if $a is greater than or equal to $b, otherwise false
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param int|float $a
     * @param int|float $b
     * @return bool
     */
    public function isGreaterThanOrEqual($a, $b): bool
    {
        array_reduce([$a, $b], [$this, 'enforcePredicate'], [$this, 'isNumber']);
        return $a >= $b;
    }

    /**
     * Returns true if $a is smaller than or equal to $b, otherwise false
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param int|float $a
     * @param int|float $b
     * @return bool
     */
    public function isSmallerThanOrEqual($a, $b): bool
    {
        array_reduce([$a, $b], [$this, 'enforcePredicate'], [$this, 'isNumber']);
        return $a <= $b;
    }

    /**
     * Returns true if $a is smaller than $b, otherwise false
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param int|float $a
     * @param int|float $b
     * @return bool
     */
    public function isSmallerThan($a, $b): bool
    {
        array_reduce([$a, $b], [$this, 'enforcePredicate'], [$this, 'isNumber']);
        return $a < $b;
    }

    /**
     * @param int[]|float[] ...$args
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isEqual(...$args): bool
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);

        $arg = $args[0];
        $args = \array_slice($args, 1);

        foreach ($args as $other) {
            if ($arg != $other) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $num
     * @return bool
     */
    public function isNumber($num): bool
    {
        return !\is_string($num) && is_numeric($num);
    }

    /**
     * Scheme's rules for integerness are slightly different than PHP's:
     *  An integer can be a "float" but it must have `.0`
     *
     * @param mixed $num
     * @return bool
     */
    public function isInteger($num): bool
    {
        return \is_int($num) ?: $this->isNumber($num) && $num == (int)$num;
    }

    /**
     * Not implemented yet.
     * Requires complex number support
     *
     * @param $num
     * @return bool
     * @throws \Error
     * @SuppressWarnings(PHPMD)
     */
    public function isComplex($num): bool
    {
        throw new \Error('Not implemented');
    }

    /**
     * Not implemented yet.
     * Requires complex number support
     *
     * @param $num
     * @return bool
     * @throws \Error
     * @SuppressWarnings(PHPMD)
     */
    public function isReal($num): bool
    {
        throw new \Error('Not implemented');
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isExact($num): bool
    {
        $this->enforcePredicate([$this, 'isNumber'], $num);

        return !\is_float($num);
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isInexact($num): bool
    {
        return !$this->isExact($num);
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isZero($num): bool
    {
        $this->enforcePredicate([$this, 'isNumber'], $num);

        return $num == 0;
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isPositive($num): bool
    {
        $this->enforcePredicate([$this, 'isNumber'], $num);

        return $num > 0;
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isNegative($num): bool
    {
        return !$this->isPositive($num);
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isOdd($num): bool
    {
        $this->enforcePredicate([$this, 'isNumber'], $num);

        return $num % 2 != 0;
    }

    /**
     * @param $num
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isEven($num): bool
    {
        return !$this->isOdd($num);
    }

    /**
     * @param int[]|float[] ...$args
     * @return int|float
     * @throws \InvalidArgumentException
     */
    public function max(...$args)
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);

        return max(...$args);
    }

    /**
     * @param int[]|float[] ...$args
     * @return int|float
     * @throws \InvalidArgumentException
     */
    public function min(...$args)
    {
        array_reduce($args, [$this, 'enforcePredicate'], [$this, 'isNumber']);

        return min(...$args);
    }

    /**
     * @param int|float $arg
     * @return int|float
     */
    public function abs($arg)
    {
        $this->enforcePredicate([$this, 'isNumber'], $arg);

        return abs($arg);
    }

    /**
     * Used as the callback to array_reduce, with the "carry" argument being the predicate to enforce
     *
     * @param callable $pred
     * @param $thingToCheck
     * @return callable
     * @throws \InvalidArgumentException
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function enforcePredicate(callable $pred, $thingToCheck): callable
    {
        if (!$pred($thingToCheck)) {
            throw new \InvalidArgumentException(static::INVALID_ARG_REQUIRES_NUMERIC);
        }

        return $pred;
    }
}
