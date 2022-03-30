<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2016 Online Ventures Software & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     7.7
 */

/**
 * range form element
 */

namespace Ppb\Form\Element;

use Cube\Form\Element\Select;

class RangeSelect extends Select
{

    const RANGE_FROM = '0';
    const RANGE_TO = '1';

    /**
     *
     * type of element - override the variable from the parent class
     *
     * @var string
     */
    protected $_element = 'range';

    /**
     *
     * class constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setMultiple(true);
    }


    /**
     *
     * return the value(s) of the element, either the element's data or default value(s)
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getValue($key = null)
    {
        $value = parent::getValue();

        if ($key !== null) {
            if (array_key_exists($key, (array)$value)) {
                return $value[$key];
            }
            else {
                return null;
            }
        }

        return $value;
    }

    /**
     *
     * render composite element
     *
     * @return string
     */
    public function render()
    {
        $output = null;

        $translate = $this->getTranslate();

        ## FROM FIELD
        $output .=
            $this->getPrefix()
            . ' <select name="' . $this->_name . '[' . self::RANGE_FROM . ']' . '" '
            . $this->renderAttributes() . '>';

        $hideDefault = $this->isHideDefault();

        if (!$hideDefault) {
            $output .= '<option value="" selected>' . $translate->_('-- select --') . '</option>';
        }

        $output .= '<optgroup disabled hidden></optgroup>';

        $value = $this->getValue(self::RANGE_FROM);

        foreach ((array)$this->_multiOptions as $key => $option) {
            $selected = (in_array($key, (array)$value)) ? 'selected' : '';

            if (is_array($option)) {
                $title = isset($option[0]) ? $option[0] : null;

                if (isset($option[1])) {
                    $this->addMultiOptionAttributes($key, $option[1]);
                }
            }
            else {
                $title = $option;
            }

            $attributes = array(
                'value="' . $key . '"',
                $this->renderOptionAttributes($key),
                $selected
            );

            $output .= '<option ' . implode(' ', array_filter($attributes)) . '>'
                . $translate->_($title) . '</option>';
        }

        $output .= '</select> '
            . $this->getSuffix();
        ## ./FROM FIELD

        $output .= ' - ';

        ## TO FIELD
        $output .=
            $this->getPrefix()
            . ' <select name="' . $this->_name . '[' . self::RANGE_TO . ']' . '" '
            . $this->renderAttributes() . '>';

        $hideDefault = $this->isHideDefault();

        if (!$hideDefault) {
            $output .= '<option value="" selected>' . $translate->_('-- select --') . '</option>';
        }

        $output .= '<optgroup disabled hidden></optgroup>';

        $value = $this->getValue(self::RANGE_TO);

        foreach ((array)$this->_multiOptions as $key => $option) {
            $selected = (in_array($key, (array)$value)) ? 'selected' : '';

            if (is_array($option)) {
                $title = isset($option[0]) ? $option[0] : null;

                if (isset($option[1])) {
                    $this->addMultiOptionAttributes($key, $option[1]);
                }
            }
            else {
                $title = $option;
            }

            $attributes = array(
                'value="' . $key . '"',
                $this->renderOptionAttributes($key),
                $selected
            );

            $output .= '<option ' . implode(' ', array_filter($attributes)) . '>'
                . $translate->_($title) . '</option>';
        }

        $output .= '</select> '
            . $this->getSuffix();
        ## ./TO FIELD

        return $output;
    }

}

