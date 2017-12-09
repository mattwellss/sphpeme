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

    if ($val === '#t') {
        return new Scalar(true);
    }

    if ($val === '#f') {
        return new Scalar(false);
    }

    return Symbol::make($val);
}
