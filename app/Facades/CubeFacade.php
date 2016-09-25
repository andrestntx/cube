<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 9/25/16
 * Time: 8:59 AM
 */

namespace App\Facades;


use App\Services\CubeService;
use App\Services\InputService;

class CubeFacade
{
    protected $cubeService;

    /**
     * CubeFacade constructor.
     * @param InputService $inputService
     * @param CubeService $cubeService
     */
    public function __construct(InputService $inputService, CubeService $cubeService)
    {
        $this->inputService = $inputService;
        $this->cubeService  = $cubeService;
    }


    /**
     * @param $string
     * @return array
     */
    public function queries($string)
    {
        $tests = $this->inputService->getTestsArray($string);
        $result = [];

        foreach($tests as $test) {
            $cube = $this->cubeService->createCube($test["dimensions"]);
            array_push($result, $this->cubeService->queries($cube, $test["queries"]));
        }

        return $result;
    }
}