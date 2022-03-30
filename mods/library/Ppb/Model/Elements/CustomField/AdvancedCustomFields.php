<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2018 Online Ventures Software & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     8.0 [rev.8.0.01]
 */
/**
 * MOD:- ADVANCED CUSTOM FIELDS
 */
/**
 * CUSTOM WORK:- ABLE IT
 */

/**
 * class that will generate extra elements for the admin elements model
 * we can have a multiple number of such classes, they just need to have a different name
 * any elements in this class will override original elements
 */

namespace Ppb\Model\Elements\CustomField;

use Ppb\Model\Elements\AbstractElements;

class AdvancedCustomFields extends AbstractElements
{

    /**
     *
     * related class
     *
     * @var bool
     */
    protected $_relatedClass = true;

    ## -- ADD -- [ MOD:- ADVANCED CUSTOM FIELDS ]

    /**
     *
     * element types allowed
     *
     * @var array
     */
    protected $_elements = array(
        'text'                            => 'text',
        'select'                          => 'select',
        'radio'                           => 'radio',
        'checkbox'                        => 'checkbox',
        'password'                        => 'password',
        'textarea'                        => 'textarea',
        ## -- ADD -- [ MOD:- ADVANCED CUSTOM FIELDS ]
        '\Ppb\Form\Element\DateInputmask' => 'Date',
        '\Ppb\Form\Element\YearInputmask' => 'Year',
//        '\Ppb\Form\Element\Date'     => 'Date',
//        '\Ppb\Form\Element\DateTime' => 'DateTime',
        ## -- ./ADD -- [ MOD:- ADVANCED CUSTOM FIELDS ]
    );

    /**
     *
     * element validators available
     * only simple default validators are enabled for now
     *
     * @var array
     */
    protected $_customFieldValidators = array(
        'Alphanumeric' => 'Alphanumeric',
        'Digits'       => 'Digits',
        'Email'        => 'Email',
        'NoHtml'       => 'NoHtml',
        'Numeric'      => 'Numeric',
        'Phone'        => 'Phone',
        'Url'          => 'Url',
    );

    /**
     *
     * get validators
     *
     * @return array
     */
    public function getCustomFieldValidators()
    {
        return $this->_customFieldValidators;
    }

    /**
     *
     * set validators
     *
     * @param array $validators
     *
     * @return $this
     */
    public function setCustomFieldValidators($validators)
    {
        $this->_customFieldValidators = $validators;

        return $this;
    }

    /**
     *
     * get model elements
     *
     * @return array
     */
    public function getElements()
    {
        $elements = array(
            array(
                'append'       => true,
                'id'           => 'element',
                'multiOptions' => $this->_elements,
            ),
            array(
                'form_id'      => 'global',
                'id'           => 'validators',
                'element'      => 'checkbox',
                'label'        => $this->_('Validators'),
                'description'  => $this->_('Select the validators that you want to apply for the element.'),
                'multiOptions' => $this->getCustomFieldValidators(),
            ),
            array(
                'append'     => true,
                'id'         => 'searchable',
                'attributes' => array(
                    'class' => 'field-changeable',
                ),
                'bodyCode'   => "
                    <script type=\"text/javascript\">
                        function updateCustomCheckboxes() {
                            var el = $('select[name=\"element\"]').val();
                                                       
                            
                            if (el === 'text' || el === 'textarea') {
                                $('input:checkbox[name^=\"validators\"]').closest('.form-group').show();                                
                            }
                            else {
                                $('input:checkbox[name^=\"validators\"]').prop('checked', false).closest('.form-group').hide();
                            }
                                                        
//                            if ((el === 'text' || ~el.indexOf(\"Date\")) && $('input:checkbox[name=\"searchable\"]').is(':checked')) {
                            if ((el === 'text' || ~el.indexOf(\"DateInputmask\") || ~el.indexOf(\"YearInputmask\")) && $('input:checkbox[name=\"searchable\"]').is(':checked')) {
                                $('input:checkbox[name=\"is_range\"]').closest('.form-group').show();
                            }
                            else {
                                $('input:checkbox[name=\"is_range\"]').prop('checked', false).closest('.form-group').hide();
                            }
                            
                            if ((el === 'text' || ~el.indexOf(\"DateInputmask\") || ~el.indexOf(\"YearInputmask\")) && $('input:checkbox[name=\"searchable\"]').is(':checked')) {
                                $('input:checkbox[name=\"is_range\"]').closest('.form-group').show();
                            }
                            else {
                                $('input:checkbox[name=\"is_range\"]').prop('checked', false).closest('.form-group').hide();
                            }
                            
                            if (~el.indexOf(\"DateInputmask\")) {
                                $('[name=\"less_than\"]').mask('00-00-0000').attr('placeholder', 'dd-mm-yyyy').closest('.form-group').show();
                                $('[name=\"greater_than\"]').mask('00-00-0000').attr('placeholder', 'dd-mm-yyyy').closest('.form-group').show();
                            }
                            else if (~el.indexOf(\"YearInputmask\")) {
                                $('[name=\"less_than\"]').mask('0000').attr('placeholder', 'yyyy').closest('.form-group').show();
                                $('[name=\"greater_than\"]').mask('0000').attr('placeholder', 'yyyy').closest('.form-group').show();
                            }
                            else {
                                $('[name=\"less_than\"]').val('').unmask().attr('placeholder', '').closest('.form-group').hide();
                                $('[name=\"greater_than\"]').val('').unmask().attr('placeholder', '').closest('.form-group').hide();
                            }
                            
                        }

                        $(document).ready(function() {             
                            updateCustomCheckboxes();
                        });

                        $(document).on('change', '.field-changeable', function() {
                            updateCustomCheckboxes();
                        });
                    </script>",
            ),
            array(
                'form_id'      => 'global',
                'after'        => array('id', 'searchable'),
                'id'           => 'is_range',
                'element'      => 'checkbox',
                'label'        => $this->_('Range'),
                'description'  => $this->_('Check above if a range type search will be generated for the element.'),
                'multiOptions' => array(
                    1 => null,
                ),
            ),
            array(
                'form_id'     => 'global',
                'id'          => 'greater_than',
                'element'     => 'text',
                'label'       => $this->_('(Validator) Greater Than'),
                'attributes'  => array(
                    'class' => 'form-control input-medium',
                ),
            ),
            array(
                'form_id'    => 'global',
                'id'         => 'less_than',
                'element'    => 'text',
                'label'      => $this->_('(Validator) Less Than'),
                'attributes' => array(
                    'class' => 'form-control input-medium',
                ),
            ),

        );


        return $elements;
    }
}

