<?php

namespace Sphpeme;


class Scalar
{
    /** @var int|float|string|bool */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

}