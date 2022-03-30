<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2021 Online Ventures Software & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     8.3 [rev.8.3.01]
 */

/**
 * checks if the variable is greater than a set value (with option to check if greater or equal)
 */

namespace Ppb\Validate;

use Cube\Validate\LessThan,
    Cube\Locale;

class DateLessThan extends LessThan
{

    /**
     *
     * max value in original format
     *
     * @var string
     */
    private $_inputMaxValue;

    /**
     *
     * get value in forced float format
     *
     * @return float
     */
    public function getValue()
    {
        return $this->_value;
    }


    /**
     *
     * set value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        parent::setValue(
            Locale\DateTime::getInstance()->localizedToDateTime($value));

        return $this;
    }

    /**
     *
     * get the maximum value accepted by the validator
     *
     * @return float
     */
    public function getMaxValue()
    {
        return $this->_maxValue;
    }

    /**
     *
     * set the maximum value the validator will compare against
     *
     * @param mixed $minValue
     *
     * @return $this
     */
    public function setMaxValue($maxValue)
    {
        $this->setInputMaxValue($maxValue);

        parent::setMaxValue(
            Locale\DateTime::getInstance()->localizedToDateTime($maxValue));

        return $this;
    }

    /**
     *
     * get input max value
     *
     * @return string
     */
    public function getInputMaxValue()
    {
        return $this->_inputMaxValue;
    }

    /**
     *
     * set input max value
     *
     * @param string $inputMaxValue
     *
     * @return $this
     */
    public function setInputMaxValue($inputMaxValue)
    {
        $this->_inputMaxValue = $inputMaxValue;

        return $this;
    }

    /**
     *
     * checks if the variable is smaller than (or equal to) the set maximum value
     *
     * @return bool          return true if the validation is successful
     */
    public function isValid()
    {
        $message = $this->getMessage();

        $isValid = parent::isValid();

        $this->setMessage(
            str_replace('%value%', $this->getInputMaxValue(), $message));

        return $isValid;
    }

}

