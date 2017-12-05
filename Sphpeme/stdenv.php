<?php

/// damn this looks like js

return (function () {

    $plus = function ($a, ...$args) use (&$plus) {
        return \count($args) > 1
            ? $a + $plus(...$args)
            : $a + current($args);
    };

    $mult = function ($a, ...$args) use(&$mult) {
        return \count($args) > 1
            ? $a * $mult(...$args)
            : $a * current($args);
    };

    $subtr = function ($a, ...$args) use(&$subtr) {
        return \count($args) > 1
            ? $a - $subtr(...$args)
            : $a - current($args);
    };

    $env = [
        '+' => $plus,
        '*' => $mult,
        '-' => $subtr,
        'pi' => M_PI,
        'random' => function ($min, $max) {
            return \random_int($min, $max);
        },
        'begin' => function (...$args) {
            return $args[\count($args) - 1];
        },
        '<' => function ($x, $y) {
            return $x < $y;
        },
        '>' => function ($x, $y) {
            return $x > $y;
        },
        'list' => function (...$args) {
            return $args;
        },
        'car' => function (array $list) {
            return current($list);
        },
        'cdr' => function (array $list) {
            return \array_slice($list, 1);
        },
        'display' => function ($arg) {
            if (\is_array($arg)) {
                var_dump($arg);
            } else {
                echo $arg;
            }
        },
        'eq?' => function($x, $y) {
            return $x === $y;
        },
        'newline' => function () {
            echo PHP_EOL;
        }
    ];

    $re = new \Sphpeme\Env();
    foreach ($env as $name => $value) {
        $re->$name = $value;
    }

    return $re;
})();