<?php

namespace Matrix\Decomposition;

use Matrix\Exception;
use Matrix\Matrix;

class QR
{
    private $qrMatrix;
    private $rows;
    private $columns;

    private $rDiagonal = [];

    public function __construct(Matrix $matrix)
    {
        $this->qrMatrix = $matrix->toArray();
        $this->rows = $matrix->rows;
        $this->columns = $matrix->columns;

        $this->decompose();
    }

<<<<<<< HEAD
    public function getHouseholdVectors(): Matrix
=======
    public function getHouseholdVectors()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $householdVectors = [];
        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                if ($row >= $column) {
                    $householdVectors[$row][$column] = $this->qrMatrix[$row][$column];
                } else {
                    $householdVectors[$row][$column] = 0.0;
                }
            }
        }

        return new Matrix($householdVectors);
    }

<<<<<<< HEAD
    public function getQ(): Matrix
=======
    public function getQ()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $qGrid = [];

        $rowCount = $this->rows;
        for ($k = $this->columns - 1; $k >= 0; --$k) {
            for ($i = 0; $i < $this->rows; ++$i) {
                $qGrid[$i][$k] = 0.0;
            }
            $qGrid[$k][$k] = 1.0;
            if ($this->columns > $this->rows) {
                $qGrid = array_slice($qGrid, 0, $this->rows);
            }

            for ($j = $k; $j < $this->columns; ++$j) {
                if (isset($this->qrMatrix[$k], $this->qrMatrix[$k][$k]) && $this->qrMatrix[$k][$k] != 0.0) {
                    $s = 0.0;
                    for ($i = $k; $i < $this->rows; ++$i) {
                        $s += $this->qrMatrix[$i][$k] * $qGrid[$i][$j];
                    }
                    $s = -$s / $this->qrMatrix[$k][$k];
                    for ($i = $k; $i < $this->rows; ++$i) {
                        $qGrid[$i][$j] += $s * $this->qrMatrix[$i][$k];
                    }
                }
            }
        }

        array_walk(
            $qGrid,
            function (&$row) use ($rowCount) {
                $row = array_reverse($row);
                $row = array_slice($row, 0, $rowCount);
            }
        );

        return new Matrix($qGrid);
    }

<<<<<<< HEAD
    public function getR(): Matrix
=======
    public function getR()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        $rGrid = [];

        for ($row = 0; $row < $this->columns; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                if ($row < $column) {
<<<<<<< HEAD
                    $rGrid[$row][$column] = $this->qrMatrix[$row][$column] ?? 0.0;
                } elseif ($row === $column) {
                    $rGrid[$row][$column] = $this->rDiagonal[$row] ?? 0.0;
=======
                    $rGrid[$row][$column] = isset($this->qrMatrix[$row][$column]) ? $this->qrMatrix[$row][$column] : 0.0;
                } elseif ($row === $column) {
                    $rGrid[$row][$column] = isset($this->rDiagonal[$row]) ? $this->rDiagonal[$row] : 0.0;
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
                } else {
                    $rGrid[$row][$column] = 0.0;
                }
            }
        }

        if ($this->columns > $this->rows) {
            $rGrid = array_slice($rGrid, 0, $this->rows);
        }

        return new Matrix($rGrid);
    }

<<<<<<< HEAD
    private function hypo($a, $b): float
=======
    private function hypo($a, $b)
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        if (abs($a) > abs($b)) {
            $r = $b / $a;
            $r = abs($a) * sqrt(1 + $r * $r);
        } elseif ($b != 0.0) {
            $r = $a / $b;
            $r = abs($b) * sqrt(1 + $r * $r);
        } else {
            $r = 0.0;
        }

        return $r;
    }

    /**
     * QR Decomposition computed by Householder reflections.
     */
<<<<<<< HEAD
    private function decompose(): void
=======
    private function decompose()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        for ($k = 0; $k < $this->columns; ++$k) {
            // Compute 2-norm of k-th column without under/overflow.
            $norm = 0.0;
            for ($i = $k; $i < $this->rows; ++$i) {
                $norm = $this->hypo($norm, $this->qrMatrix[$i][$k]);
            }
            if ($norm != 0.0) {
                // Form k-th Householder vector.
                if ($this->qrMatrix[$k][$k] < 0.0) {
                    $norm = -$norm;
                }
                for ($i = $k; $i < $this->rows; ++$i) {
                    $this->qrMatrix[$i][$k] /= $norm;
                }
                $this->qrMatrix[$k][$k] += 1.0;
                // Apply transformation to remaining columns.
                for ($j = $k + 1; $j < $this->columns; ++$j) {
                    $s = 0.0;
                    for ($i = $k; $i < $this->rows; ++$i) {
                        $s += $this->qrMatrix[$i][$k] * $this->qrMatrix[$i][$j];
                    }
                    $s = -$s / $this->qrMatrix[$k][$k];
                    for ($i = $k; $i < $this->rows; ++$i) {
                        $this->qrMatrix[$i][$j] += $s * $this->qrMatrix[$i][$k];
                    }
                }
            }
            $this->rDiagonal[$k] = -$norm;
        }
    }

<<<<<<< HEAD
    public function isFullRank(): bool
=======
    /**
     * @return bool
     */
    public function isFullRank()
>>>>>>> 3969f7788d58140d0538f44130a6184fdf989a37
    {
        for ($j = 0; $j < $this->columns; ++$j) {
            if ($this->rDiagonal[$j] == 0.0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Least squares solution of A*X = B.
     *
     * @param Matrix $B a Matrix with as many rows as A and any number of columns
     *
     * @throws Exception
     *
     * @return Matrix matrix that minimizes the two norm of Q*R*X-B
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

        if (!$this->isFullRank()) {
            throw new Exception('Can only perform this operation on a full-rank matrix');
        }

        // Compute Y = transpose(Q)*B
        $Y = $this->getQ()->transpose()
            ->multiply($B);
        // Solve R*X = Y;
        return $this->getR()->inverse()
            ->multiply($Y);
    }
}
