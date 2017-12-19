<?php

namespace Sphpeme;

class Pair
{
    private $value;
    private $tail;

    public static function list($arg, ...$args)
    {
        if (\count($args)) {
            return static::cons($arg, static::list(...$args));
        }

        return new self($arg);
    }

    private function __construct($value)
    {
        $this->value = $value;
    }

    public function car()
    {
        return $this->value;
    }

    public function cdr()
    {
        return $this->tail;
    }

    public static function cons($value, $pair)
    {
        $l = new static($value);
        $l->tail = $pair;
        return $l;
    }

    public function toArray(): array
    {
        $arr = [];
        $thing = clone $this;
        do {
            $arr[] = $thing->value instanceof Pair
                ? $thing->value->toArray()
                : $thing->value;
            $thing = $thing->tail;
        } while ($thing);

        return $arr;
    }
}
