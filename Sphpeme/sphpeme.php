<?php

namespace Sphpeme;

/**
 * Parse the given value as its scalar type
 *
 * @param $val
 * @return mixed
 */
function atom($val)
{
    if (is_numeric($val)) {
        return new Scalar(strpos($val, '.')
            ? (float)$val
            : (int)$val);
    }

    if (strpos($val, '"') === 0) {
        return new Scalar(str_replace('"', '', $val));
    }

    if (\is_bool($val)) {
        return new Scalar($val);
    }

    return Symbol::make($val);
}

/**
 * Extend the given env with the extension values
 *
 * @param Env $env
 * @param array $extends
 * @return Env
 */
function env_extend(Env $env, array $extends)
{
    $myenv = clone $env;
    foreach ($extends as $key => $value) {
        $myenv->$key = $value;
    }

    return $myenv;
}


/**
 * Provides access to Sphpeme's std env
 *
 * @return Env
 */
function get_std_env(): Env
{
    return require __DIR__ . '/stdenv.php';
}
