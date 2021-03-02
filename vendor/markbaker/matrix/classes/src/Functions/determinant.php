<?php

/**
 *
 * Function code for the matrix determinant() function
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */

namespace Matrix;

/**
 * Returns the determinant of a matrix or an array.
 *
 * @param Matrix|array $matrix Matrix or an array to treat as a matrix.
 * @return float Matrix determinant
 * @throws Exception If argument isn't a valid matrix or array.
 */
<<<<<<< HEAD
function determinant($matrix): float
=======
function determinant($matrix)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
{
    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Must be Matrix or array');
    }

    return Functions::determinant($matrix);
}
