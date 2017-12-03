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
        return strpos($val, '.')
            ? (float)$val
            : (int)$val;
    }

    if (strpos($val, '"') === 0) {
        return str_replace('"', '', $val);
    }

    return new Symbol($val);
}

/**
 * Tokenize the program string for parsing
 *
 * @param $programStr
 * @return array tokenized program
 */
function tokenize($programStr): array
{
    return array_map('trim',
        explode(' ', str_replace(['(', ')'], ['( ', ' )'], $programStr)));
}

/**
 * Generate an AST given an array of tokens
 *
 * @param array $tokens
 * @return array AST
 */
function parse(array $tokens): array
{
    $stack = [];
    foreach ($tokens as $token) {
        switch ($token) {
            case '(':
                $stack = [[], $stack];
                break;
            case ')':
                [$exp, $stack] = $stack;
                $stack[0][] = $exp;
                break;
            case strpos($token, ';;') !== 0:
                $stack[0][] = atom($token);
                break;
            default:
                break;
        }
    }

    return $stack[0][0] ?? [];
}

/**
 * Extend the given env with the extension values
 *
 * @param \stdClass $env
 * @param array $extends
 * @return \stdClass
 */
function env_extend(\stdClass $env, array $extends)
{
    $myenv = clone $env;
    foreach ($extends as $key => $value) {
        $myenv->$key = $value;
    }

    return $myenv;
}

/**
 * evaluate the AST with the env
 *
 * @param string|int|float|array $exp
 * @param \stdClass $env
 * @return mixed
 */
function evaluate($exp, \stdClass $env)
{
    if ($exp instanceof Symbol) {
        return $env->$exp;
    }

    if (\is_numeric($exp) || \is_string($exp)) {
        return $exp;
    }

    // special forms
    if ($exp[0] == 'if') {
        [$if, $test, $true, $false] = $exp;

        return evaluate(
            evaluate($test, $env)
                ? $true
                : $false,
            $env);
    }

    if ($exp[0] == 'lambda') {
        [$lambda, $params, $body] = $exp;
        return function (...$args) use ($env, $body, $params) {
            return evaluate($body, env_extend($env, array_combine($params, $args)));
        };
    }

    if ($exp[0] == 'define') {
        [$_, $symbol, $exp] = $exp;
        $env->$symbol = evaluate($exp, $env);
    } else {
        $call = evaluate($exp[0], $env);
        $args = [];
        foreach (\array_slice($exp, 1) as $arg) {
            $args[] = evaluate($arg, $env);
        }

        return $call(...$args);
    }
}

/**
 * Provides access to Sphpeme's std env
 *
 * @return \stdClass
 */
function get_std_env(): \stdClass
{
    return require __DIR__ . '/stdenv.php';
}
