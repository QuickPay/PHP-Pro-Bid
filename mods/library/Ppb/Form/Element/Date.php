<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2015 Online Ventures Software & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     7.6
 */
/**
 * date custom form element
 *
 * creates an element of type date with the datepicker jquery ui component enabled
 */
/**
 * MOD:- ADVANCED CUSTOM FIELDS
 */

namespace Ppb\Form\Element;

use Cube\Form\Element,
    Cube\Controller\Front;

class Date extends Element
{

    /**
     *
     * type of element - override the variable from the parent class
     *
     * @var string
     */
    protected $_element = 'text';

    /**
     *
     * class constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($this->_element, $name);

        $baseUrl = Front::getInstance()->getRequest()->getBaseUrl();

        $this->setHeaderCode('<link href="' . $baseUrl . '/js/bootstrap-datetimepicker/css/tempusdominus-bootstrap-4.min.css" media="screen" rel="stylesheet" type="text/css">')
            ->setBodyCode('<script type="text/javascript" src="' . $baseUrl . '/js/moment/moment-with-locales.min.js"></script>')
            ->setBodyCode('<script type="text/javascript" src="' . $baseUrl . '/js/bootstrap-datetimepicker/js/tempusdominus-bootstrap-4.min.js"></script>');

        $this->addAttribute('id', $name);

        $this->setCustomData(array());
    }

    /**
     *
     * set the custom data for the element, and add the javascript code
     *
     * @param array $customData
     *
     * @return $this
     */
    public function setCustomData($customData)
    {
        $this->_customData = $customData;

        $formData = array(
            'locale: "' . $this->getTranslate()->getLocale() . '"',
            'ignoreReadonly: true',
            'format: "L"',
        );

        if (isset($this->_customData['formData'])) {
            foreach ((array)$this->_customData['formData'] as $key => $value) {
                $formData[] = "{$key}: {$value}";
            }
        }

        $formData = implode(", \n", $formData);

        $this->setBodyCode(
            "<script type=\"text/javascript\">" . "\n"
            . " $(document).ready(function() { " . "\n"
            . "     $('#" . $this->getName() . "').datetimepicker({ " . "\n"
            . "         {$formData} " . "\n"
            . "     }); " . "\n"
            . " }); " . "\n"
            . "</script>");

        return $this;
    }

    /**
     *
     * renders the date time form element
     *
     * @return string   the html code of the element
     */
    public function render()
    {
        $value = $this->getValue();

        if (!is_string($value)) {
            $value = '';
        }
        else {
            $value = str_replace('"', '&quot;', $value);
        }

        $multiple = ($this->getMultiple() === true) ? $this->_brackets : '';

        $this->addAttribute('class', 'has-icon-right');

        $attributes = array(
            'type="' . $this->_type . '"',
            'name="' . $this->_name . $multiple . '"',
            'data-toggle="datetimepicker"',
            'data-target="#' . $this->getAttribute('id') . '"',
            'value="' . $value . '"',
            $this->renderAttributes()
        );

        return $this->getPrefix() . ' '
            . '<div class="has-icons mr-1">'
            . '<input ' . implode(' ', array_filter($attributes))
            . $this->_endTag . ' '
            . '<span class="glyphicon glyphicon-calendar icon-right"></span>'
            . '</div>'
            . ' '
            . $this->getSuffix();

    }
}

