<?php

namespace Matrix\Decomposition;

use Matrix\Exception;
use Matrix\Matrix;

class LU
{
    private $luMatrix;
    private $rows;
    private $columns;

    private $pivot = [];

    public function __construct(Matrix $matrix)
    {
        $this->luMatrix = $matrix->toArray();
        $this->rows = $matrix->rows;
        $this->columns = $matrix->columns;

        $this->buildPivot();
    }

    /**
     * Get lower triangular factor.
     *
     * @return Matrix Lower triangular factor
     */
<<<<<<< HEAD
    public function getL(): Matrix
=======
    public function getL()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $lower = [];

        $columns = min($this->rows, $this->columns);
        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $columns; ++$column) {
                if ($row > $column) {
                    $lower[$row][$column] = $this->luMatrix[$row][$column];
                } elseif ($row === $column) {
                    $lower[$row][$column] = 1.0;
                } else {
                    $lower[$row][$column] = 0.0;
                }
            }
        }

        return new Matrix($lower);
    }

    /**
     * Get upper triangular factor.
     *
     * @return Matrix Upper triangular factor
     */
<<<<<<< HEAD
    public function getU(): Matrix
=======
    public function getU()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $upper = [];

        $rows = min($this->rows, $this->columns);
        for ($row = 0; $row < $rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                if ($row <= $column) {
                    $upper[$row][$column] = $this->luMatrix[$row][$column];
                } else {
                    $upper[$row][$column] = 0.0;
                }
            }
        }

        return new Matrix($upper);
    }

    /**
     * Return pivot permutation vector.
     *
     * @return Matrix Pivot matrix
     */
<<<<<<< HEAD
    public function getP(): Matrix
=======
    public function getP()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $pMatrix = [];

        $pivots = $this->pivot;
        $pivotCount = count($pivots);
        foreach ($pivots as $row => $pivot) {
            $pMatrix[$row] = array_fill(0, $pivotCount, 0);
            $pMatrix[$row][$pivot] = 1;
        }

        return new Matrix($pMatrix);
    }

    /**
     * Return pivot permutation vector.
     *
     * @return array Pivot vector
     */
<<<<<<< HEAD
    public function getPivot(): array
=======
    public function getPivot()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        return $this->pivot;
    }

    /**
     *    Is the matrix nonsingular?
     *
     * @return bool true if U, and hence A, is nonsingular
     */
<<<<<<< HEAD
    public function isNonsingular(): bool
=======
    public function isNonsingular()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        for ($diagonal = 0; $diagonal < $this->columns; ++$diagonal) {
            if ($this->luMatrix[$diagonal][$diagonal] === 0.0) {
                return false;
            }
        }

        return true;
    }

<<<<<<< HEAD
    private function buildPivot(): void
=======
    private function buildPivot()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            $this->pivot[$row] = $row;
        }

        for ($column = 0; $column < $this->columns; ++$column) {
            $luColumn = $this->localisedReferenceColumn($column);

            $this->applyTransformations($column, $luColumn);

            $pivot = $this->findPivot($column, $luColumn);
            if ($pivot !== $column) {
                $this->pivotExchange($pivot, $column);
            }

            $this->computeMultipliers($column);

            unset($luColumn);
        }
    }

<<<<<<< HEAD
    private function localisedReferenceColumn($column): array
=======
    private function localisedReferenceColumn($column)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $luColumn = [];

        for ($row = 0; $row < $this->rows; ++$row) {
            $luColumn[$row] = &$this->luMatrix[$row][$column];
        }

        return $luColumn;
    }

<<<<<<< HEAD
    private function applyTransformations($column, array $luColumn): void
=======
    private function applyTransformations($column, array $luColumn)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            $luRow = $this->luMatrix[$row];
            // Most of the time is spent in the following dot product.
            $kmax = min($row, $column);
            $sValue = 0.0;
            for ($kValue = 0; $kValue < $kmax; ++$kValue) {
                $sValue += $luRow[$kValue] * $luColumn[$kValue];
            }
            $luRow[$column] = $luColumn[$row] -= $sValue;
        }
    }

<<<<<<< HEAD
    private function findPivot($column, array $luColumn): int
=======
    private function findPivot($column, array $luColumn)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $pivot = $column;
        for ($row = $column + 1; $row < $this->rows; ++$row) {
            if (abs($luColumn[$row]) > abs($luColumn[$pivot])) {
                $pivot = $row;
            }
        }

        return $pivot;
    }

<<<<<<< HEAD
    private function pivotExchange($pivot, $column): void
=======
    private function pivotExchange($pivot, $column)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        for ($kValue = 0; $kValue < $this->columns; ++$kValue) {
            $tValue = $this->luMatrix[$pivot][$kValue];
            $this->luMatrix[$pivot][$kValue] = $this->luMatrix[$column][$kValue];
            $this->luMatrix[$column][$kValue] = $tValue;
        }

        $lValue = $this->pivot[$pivot];
        $this->pivot[$pivot] = $this->pivot[$column];
        $this->pivot[$column] = $lValue;
    }

<<<<<<< HEAD
    private function computeMultipliers($diagonal): void
=======
    private function computeMultipliers($diagonal)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        if (($diagonal < $this->rows) && ($this->luMatrix[$diagonal][$diagonal] != 0.0)) {
            for ($row = $diagonal + 1; $row < $this->rows; ++$row) {
                $this->luMatrix[$row][$diagonal] /= $this->luMatrix[$diagonal][$diagonal];
            }
        }
    }

<<<<<<< HEAD
    private function pivotB(Matrix $B): array
=======
    private function pivotB(Matrix $B)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $X = [];
        foreach ($this->pivot as $rowId) {
            $row = $B->getRows($rowId + 1)->toArray();
            $X[] = array_pop($row);
        }

        return $X;
    }

    /**
     * Solve A*X = B.
     *
     * @param Matrix $B a Matrix with as many rows as A and any number of columns
     *
     * @throws Exception
     *
     * @return Matrix X so that L*U*X = B(piv,:)
     */
<<<<<<< HEAD
    public function solve(Matrix $B): Matrix
=======
    public function solve(Matrix $B)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        if ($B->rows !== $this->rows) {
            throw new Exception('Matrix row dimensions are not equal');
        }

        if ($this->rows !== $this->columns) {
            throw new Exception('LU solve() only works on square matrices');
        }

        if (!$this->isNonsingular()) {
            throw new Exception('Can only perform operation on singular matrix');
        }

        // Copy right hand side with pivoting
        $nx = $B->columns;
        $X = $this->pivotB($B);

        // Solve L*Y = B(piv,:)
        for ($k = 0; $k < $this->columns; ++$k) {
            for ($i = $k + 1; $i < $this->columns; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->luMatrix[$i][$k];
                }
            }
        }

        // Solve U*X = Y;
        for ($k = $this->columns - 1; $k >= 0; --$k) {
            for ($j = 0; $j < $nx; ++$j) {
                $X[$k][$j] /= $this->luMatrix[$k][$k];
            }
            for ($i = 0; $i < $k; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->luMatrix[$i][$k];
                }
            }
        }

        return new Matrix($X);
    }
}
