<?php

namespace Sphpeme\Env;


use Sphpeme\Env;

class StdEnv extends Env
{
    const MAPPING = [
        '+' => 'add',
        '-' => 'subtract',
        '*' => 'multiply',
        '/' => 'divide',
        '>' => 'gt',
        '<' => 'lt',
        '<=' => 'lte',
        '>=' => 'gte',
        'eq?' => 'isEqual',
        'eqv?' => 'isEquiv',
    ];

    public function __get($name)
    {
        if (isset(static::MAPPING[$name])) {
            return $this->__get(static::MAPPING[$name]);
        }

        return \is_callable([$this, $name])
            ? [$this, $name]
            : $this->$name;
    }

    public $pi = M_PI;

    public function add($first, ...$rest)
    {
        return $first + (\count($rest) > 1
                ? $this->add(...$rest)
                : current($rest));
    }

    public function subtract($first, ...$rest)
    {
        return $first - (\count($rest) > 1
                ? $this->add(...$rest)
                : current($rest));
    }

    public function multiply($first, ...$rest)
    {
        return $first * (\count($rest) > 1
                ? $this->multiply(...$rest)
                : current($rest));
    }

    public function divide($a, $b)
    {
        return $a / $b;
    }

    public function gt($a, $b)
    {
        return $a > $b;
    }

    public function lt($a, $b)
    {
        return $a < $b;
    }

    public function gte($a, $b)
    {
        return $a >= $b;
    }

    public function lte($a, $b)
    {
        return $a <= $b;
    }

    public function isEqual($a, $b)
    {
        return $a == $b;
    }

    public function isEquiv($a, $b)
    {
        return $a === $b;
    }

}