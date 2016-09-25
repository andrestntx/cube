<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 9/25/16
 * Time: 2:24 PM
 */

namespace App\Services;


class InputService
{
    /**
     * @param $string
     * @return array
     */
    protected function getLines($string)
    {
        return explode(PHP_EOL, str_replace("\r", "", $string));
    }

    /**
     * @param $string
     * @return array
     */
    protected function getLine($string)
    {
        return explode(" ", $string);
    }

    /**
     * @param array $line
     * @return bool
     */
    protected function isConfigTest(array $line)
    {
        return count($line) == 2;
    }

    /**
     * @param array $line
     * @param $name
     * @param $items
     * @return bool
     */
    protected function isQueryType(array $line, $name, $items)
    {
        return (count($line) == $items && $line[0] == $name);
    }

    /**
     * @param array $line
     * @return bool
     */
    protected function isUpdate(array $line)
    {
        return $this->isQueryType($line, "UPDATE", 5);
    }

    /**
     * @param array $line
     * @return bool
     */
    protected function isQuery(array $line)
    {
        return $this->isQueryType($line, "QUERY", 7);
    }

    /**
     * @param array $line
     * @return array
     */
    protected function getUpdate(array $line)
    {
        return ['type' => 'update', 'index' => ['x' => $line[1], 'y' => $line[2], 'z' => $line[3]], 'value' => $line[4]];
    }

    /**
     * @param array $line
     * @return array
     */
    protected function getQuery(array $line)
    {
        return ['type' => 'query', 'index' => ['x' => [$line[1],$line[4]],  'y' => [$line[2],$line[5]], 'z' => [$line[3],$line[6]]]];
    }

    /**
     * @param array $line
     * @return array
     */
    protected function getConfigTest(array $line)
    {
        return [
            "dimensions"    => $line[0],
            "queries"       => []
        ];

    }

    /**
     * @param $string
     * @return array
     */
    public function getTestsArray($string)
    {
        $lines      = $this->getLines($string);
        $test       = 0;
        $queries    = [];

        foreach ($lines as $key => $line) {
            $line = $this->getLine($line);

            if($this->isConfigTest($line)) {
                $test++;
                $queries[$test] = $this->getConfigTest($line);
            }
            elseif($this->isUpdate($line)) {
                array_push($queries[$test]["queries"], $this->getUpdate($line));
            }
            elseif($this->isQuery($line)) {
                array_push($queries[$test]["queries"], $this->getQuery($line));
            }
        }

        return $queries;
    }

}