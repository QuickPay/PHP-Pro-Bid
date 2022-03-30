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
 * creates an element of type date with input mask
 */
/**
 * MOD:- ADVANCED CUSTOM FIELDS
 */

namespace Ppb\Form\Element;

use Cube\Form\Element,
    Cube\Controller\Front;

class DateInputmask extends Element
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

        $this->setBodyCode('<script type="text/javascript" src="' . $baseUrl . '/mods/js/jquery.mask.min.js"></script>');
        $this->setBodyCode(
            "<script type=\"text/javascript\">" . "\n"
            . " $(document).ready(function() { " . "\n"
            . "     $('#" . $this->getName() . "').mask('00-00-0000'); " . "\n"
            . " }); " . "\n"
            . "</script>");

        $this->addAttribute('id', $name)
            ->addAttribute('placeholder', 'dd-mm-yyyy');

        $this->setCustomData(array());
    }

}

