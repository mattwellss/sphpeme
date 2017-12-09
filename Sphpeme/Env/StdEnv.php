<?php

namespace Sphpeme\Env;



class StdEnv implements EnvInterface
{
    const MAPPING = [
        '#' => '+',
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
        'list' => '_list'
    ];

    public function __isset($prop): bool
    {
        return (bool)$this->__get($prop);
    }

    public function __get(string $prop)
    {
        if (isset(static::MAPPING[$prop])) {
            return $this->__get(static::MAPPING[$prop]);
        }

        return \is_callable([$this, $prop])
            ? [$this, $prop]
            : $this->$prop;
    }

    public $pi = M_PI;

    public function _list(...$args)
    {
        return $args;
    }

    public function car(array $list)
    {
        return current($list);
    }

    public function cdr(array $list)
    {
        return \array_slice($list, 1);
    }

    public function display($arg)
    {
        if (is_scalar($arg) || method_exists($arg, '__toString')) {
            echo $arg;
        } else {
            var_export($arg);
        }
    }

    public function newline()
    {
        echo PHP_EOL;
    }

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