<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 9/25/16
 * Time: 8:48 AM
 */

namespace App\Entities;


class Cube
{
    protected $dimensions;
    protected $matrix;

    /**
     * Cube constructor.
     * @param $dimensions
     * @param int $init
     */
    public function __construct($dimensions, $init = 0)
    {
        $this->setValues($dimensions, $init);
        $this->dimensions = $dimensions;
    }

    /**
     * @param $dimensions
     * @param $value
     */
    protected function setValues($dimensions, $value)
    {
        for($x = 1; $x <= $dimensions; $x ++) {
            for($y = 1; $y <= $dimensions; $y ++) {
                for($z = 1; $z <= $dimensions; $z ++) {
                    $this->setValue($x, $y, $z, $value);
                }
            }
        }
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param int $value
     */
    public function setValue($x, $y, $z, $value)
    {
        $this->matrix[$x][$y][$z] = $value;
    }

    /**
     * @param $x
     * @param $y
     * @param $z
     * @return mixed
     */
    public function getValue($x, $y, $z)
    {
        return $this->matrix[$x][$y][$z];
    }


}