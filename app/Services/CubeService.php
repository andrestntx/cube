<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 9/25/16
 * Time: 8:46 AM
 */

namespace App\Services;


use App\Entities\Cube;

class CubeService
{
    /**
     * @param int $dimensions
     * @return Cube
     */
    public function createCube($dimensions)
    {
        return new Cube($dimensions);
    }


    /**
     * @param Cube $cube
     * @param int $x
     * @param int $y
     * @param int $z
     * @param int $value
     * @return Cube
     */
    public function updateCube(Cube &$cube, $x, $y, $z, $value)
    {
        $cube->setValue($x, $y, $z, $value);
        return $cube;
    }

    /**
     * @param Cube $cube
     * @param $x1
     * @param $y1
     * @param $z1
     * @param $x2
     * @param $y2
     * @param $z2
     * @return int|mixed
     */
    protected function queryCube(Cube $cube, $x1, $y1, $z1, $x2, $y2, $z2)
    {
        $result = 0;

        for($x = $x1; $x <= $x2; $x ++) {
            for($y = $y1; $y <= $y2; $y ++) {
                for($z = $z1; $z <= $z2; $z ++) {
                    $result += $cube->getValue($x, $y, $z);
                }
            }
        }

        return $result;
    }

    /**
     * @param Cube $cube
     * @param array $x
     * @param array $y
     * @param array $z
     * @return int|mixed
     */
    protected function queryCubeSimple(Cube $cube, array $x, array $y, array $z)
    {
        return $this->queryCube($cube, $x[0], $y[0], $z[0], $x[1], $y[1], $z[1]);
    }

    /**
     * @param Cube $cube
     * @param array $queries
     * @return array
     */
    public function queries(Cube &$cube, array $queries)
    {
        $results = [];

        foreach($queries as $query) {
            $index = $query['index'];

            if($query['type'] == 'update') {
                $this->updateCube($cube, $index['x'], $index['y'], $index['z'], $query['value']);
            }
            else {
                array_push($results, $this->queryCubeSimple($cube, $index['x'], $index['y'], $index['z']));
            }
        }

        return $results;
    }
}