<?php

namespace Sphpeme\Env;

use Sphpeme\Env\EnvInterface;
use Sphpeme\Env\MappedEnvTrait;

class BoolEnv implements EnvInterface
{
    use MappedEnvTrait;

    const MAPPING = [
        'boolean?' => 'isBool',
        'eqv?' => 'isEqv',
        'eq?' => 'isEq',
        'equal?' => 'isEqual'
    ];

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param $a
     * @param $b
     * @return bool
     */
    public function isEqv($a, $b)
    {
        // todo: follow definition of eqv?
        return $a == $b;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param $a
     * @param $b
     * @return bool
     */
    public function isEq($a, $b)
    {
        return $a === $b;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param $a
     * @param $b
     * @return bool
     */
    public function isEqual($a, $b)
    {
        return $a == $b;
    }

    /**
     * @param $arg
     * @return bool
     */
    public function not($arg): bool
    {
        return !$arg;
    }

    /**
     * @param $arg
     * @return bool
     */
    public function isBool($arg)
    {
        return \is_bool($arg);
    }
}
