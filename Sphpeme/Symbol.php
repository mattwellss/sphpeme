<?php

namespace Sphpeme;


class Symbol
{
    /** @var string */
    private $value;

    /** @var array */
    public static $table = [];

    public static function make($sym): self
    {
        if (isset(static::$table[$sym])) {
            return static::$table[$sym];
        }

        static::$table[$sym] = new static($sym);

        return static::$table[$sym];
    }

    private function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

}