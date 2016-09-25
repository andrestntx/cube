<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 9/23/16
 * Time: 4:19 PM
 */

namespace App\Validation;


class QueriesCubeValidation
{

    protected $nTestCases = 0;
    protected $result = true;
    protected $error = ["line" => 1, "text" => ""];

    /**
     * @param $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected function isNumberRange($value, $min, $max = null)
    {
        if($value >= $min) {
            if(! is_null($max) && $value > $max) {
                return false;
            }

            return $value;
        }

        return false;
    }

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
     * @param $numberItems
     * @return array|bool
     */
    protected function getLine($string, $numberItems)
    {
        $line = explode(" ", $string);

        if(count($line) == $numberItems) {
            return $line;
        }

        return false;
    }

    /**
     * @param $string
     * @return array|bool
     */
    protected function getConfigTestCase($string)
    {
        if($line = $this->getLine($string, 2)) {
            if($this->isNumberRange($line[0], 1, 100) && $this->isNumberRange($line[1], 1, 1000)) {
                return [
                    "dimensions"    => $line[0],
                    "numberQueries" => $line[1]
                ];
            }
        }

        return false;
    }

    /**
     * @param $string
     * @param $queryType
     * @param $numberOptions
     * @return array|bool
     */
    protected function getQueryType($string, $queryType, $numberOptions)
    {
        if($line = $this->getLine($string, $numberOptions)) {
            if($line[0] == $queryType) {
                return $line;
            }
        }

        return false;
    }

    /**
     * @param $string
     * @return bool
     */
    protected function isUpdateLine($string)
    {
        if($line = $this->getQueryType($string, "UPDATE", 5)) {
            return ($line[1] > 0 && $line[2] > 0 && $line[3] > 0);
        }

        return false;
    }

    /**
     * @param $string
     * @return bool
     */
    protected function isQueryLine($string)
    {
        if($line = $this->getQueryType($string, "QUERY", 7)) {
            return ($this->isNumberRange($line[1], 1, $line[4]) && $this->isNumberRange($line[2], 1, $line[5])
                && $this->isNumberRange($line[3], 1, $line[6]));
        }

        return false;
    }

    protected function setConfigTestCaseError()
    {
        $this->result = false;
        $this->error["text"] = \Lang::get("validation.cube.config-test-case");
    }

    protected function setQueryLineError()
    {
        $this->result = false;
        $this->error["text"] = \Lang::get("validation.cube.query-line");
    }

    protected function setFirstLineError()
    {
        $this->result = false;
        $this->error["text"] = \Lang::get("validation.cube.first-line");
    }

    protected function setMaxTestsError()
    {
        $this->result = false;
        $this->error["text"] = \Lang::get("validation.cube.max-tests");
    }

    protected function setSyntaxError($line)
    {
        $this->result = false;
        $this->error["line"] = $line;
        $this->error["text"] = \Lang::get("validation.cube.syntax");
    }

    protected function setTestCaseIncompleteError()
    {
        $this->result = false;
        $this->error["line"] = "NN";
        $this->error["text"] = \Lang::get("validation.cube.tests-case-incomplete");
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        $lines = $this->getLines($value);
        \Log::info($lines);
        $countTest = 1;
        $countQuery = 1;

        if(count($lines) >= 3 ) {
            if ($numberTestCases = $this->isNumberRange($lines[0], 1, 50)) {
                if ($configTestCase = $this->getConfigTestCase($lines[1])) {
                    foreach ($lines as $key => $line) {
                        if ($key > 1) {
                            $this->error["line"] = $key + 1;
                            if ($countQuery <= $configTestCase["numberQueries"]) {
                                if (!$this->isUpdateLine($line) && !$this->isQueryLine($line)) {
                                    $this->setQueryLineError();
                                    break;
                                }
                                $countQuery++;
                            } else if ($countTest >= $numberTestCases) {
                                $this->setMaxTestsError();
                                break;
                            } else if ($configTestCase = $this->getConfigTestCase($line)) {
                                $countTest++;
                                $countQuery = 1;
                            } else {
                                $this->setConfigTestCaseError();
                                break;
                            }
                        }
                    }
                    if ($this->result && $countQuery <= $configTestCase["numberQueries"]) {
                        $this->setTestCaseIncompleteError();
                    }
                } else {
                    $this->setConfigTestCaseError();
                }
            } else {
                $this->setFirstLineError();
            }
        }
        else {
            $this->setSyntaxError(count($lines));
        }

        if(! $this->result) {
            $error = $this->error;
            $validator->addReplacer('queries', function ($message, $attribute, $rule, $parameters) use ($error) {
                return str_replace(":text", $error["text"], str_replace(":line", $error["line"], $message));
            });
        }

        return $this->result;
    }
}