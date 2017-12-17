<?php

namespace Sphpeme;

class Pair
{
    private $value;
    private $tail;

    public static function list($arg, ...$args)
    {
        if (\count($args)) {
            return static::list(...$args)->cons($arg);
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

    public function cons($thing)
    {
        $l = new static($thing);
        $l->tail = $this;
        return $l;
    }

    public function toArray(): array
    {
        $arr = [];
        $thing = clone $this;
        do {
            $arr[] = $thing->value;
            $thing = $thing->tail;
        } while ($thing);

        return $arr;
    }
}
