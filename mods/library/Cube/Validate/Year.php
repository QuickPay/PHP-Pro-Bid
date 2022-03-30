<?php

/**
 *
 * Cube Framework
 *
 * @link        http://codecu.be/framework
 * @copyright   Copyright (c) 2017 CodeCube SRL
 * @license     http://codecu.be/framework/license Commercial License
 *
 * @version     1.10 [rev.1.10.01]
 */
/**
 * year validator class
 */
/**
 * CUSTOM WORK:- ABLE IT
 *
 * @WONTUSE
 */

namespace Cube\Validate;

class Year extends AbstractValidate
{

    const LESS = 1;
    const LESS_EQUAL = 2;
    const GREATER = 3;
    const GREATER_EQUAL = 4;

    protected $_messages = array(
        self::LESS          => "'%s' must be smaller than %value%.",
        self::LESS_EQUAL    => "'%s' must be smaller or equal to %value%.",
        self::GREATER       => "'%s' must be greater than %value%.",
        self::GREATER_EQUAL => "'%s' must be greater or equal to %value%.",
    );

    /**
     *
     * the minimum value allowed for the validator to check
     *
     * @var float
     */
    protected $_minValue;

    /**
     *
     * the maximum value allowed for the validator to check
     *
     * @var float
     */
    protected $_maxValue;

    /**
     *
     * if true, it will check for equal values as well
     *
     * @var bool
     */
    protected $_equal = false;


    /**
     *
     * class constructor
     *
     * initialize the minimum value allowed and the equal check
     *
     * @param array $data       data[0] -> min value;
     *                          data[1] -> max value;
     *                          data[2] -> accept equal values (default = false)
     */
    public function __construct(array $data = null)
    {
        if (isset($data[0])) {
            $this->setMinValue($data[0]);
        }
        else {
            $this->setMinValue(1920);
        }

        if (isset($data[1])) {
            $this->setMaxValue($data[1]);
        }
        else {
            $this->setMaxValue(date('Y'));
        }

        if (isset($data[2])) {
            $this->setEqual($data[2]);
        }
        else {
            $this->setEqual(true);
        }
    }

    /**
     * @return float
     */
    public function getMinValue()
    {
        return $this->_minValue;
    }

    /**
     *
     * @param float $minValue
     *
     * @return $this
     */
    public function setMinValue($minValue)
    {
        $this->_minValue = intval($minValue);

        return $this;
    }

    /**
     * @return float
     */
    public function getMaxValue()
    {
        return $this->_maxValue;
    }

    /**
     *
     * @param float $maxValue
     *
     * @return $this
     */
    public function setMaxValue($maxValue)
    {
        $this->_maxValue = intval($maxValue);

        return $this;
    }

    /**
     * @return bool
     */
    public function isEqual()
    {
        return $this->_equal;
    }

    /**
     *
     * @param bool $equal
     *
     * @return $this
     */
    public function setEqual($equal)
    {
        $this->_equal = $equal;

        return $this;
    }

    /**
     *
     * get value in forced float format
     *
     * @return float
     */
    public function getValue()
    {
        return intval(parent::getValue());
    }


    /**
     *
     * checks if the variable contains digits only
     *
     * @return bool          return true if the validation is successful
     */
    public function isValid()
    {
        $value = $this->getValue();

        if (empty($value)) {
            return true;
        }

        $minValue = $this->getMinValue();
        $maxValue = $this->getMaxValue();
        $isEqual = $this->isEqual();

        if (($isEqual && $value < $minValue) || (!$isEqual && $value <= $minValue)) {
            if ($isEqual) {
                $this->setMessage($this->_messages[self::GREATER_EQUAL]);
            }
            else {
                $this->setMessage($this->_messages[self::GREATER]);
            }

            $this->setMessage(
                str_replace('%value%', $minValue, $this->getMessage()));

            return false;
        }

        if (($isEqual && $value > $maxValue) || (!$isEqual && $value >= $minValue)) {
            if ($isEqual) {
                $this->setMessage($this->_messages[self::LESS_EQUAL]);
            }
            else {
                $this->setMessage($this->_messages[self::LESS]);
            }

            $this->setMessage(
                str_replace('%value%', $maxValue, $this->getMessage()));

            return false;
        }

        return true;
    }

}

