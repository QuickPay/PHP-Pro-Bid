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
 * class that will generate extra elements for the admin elements model
 * we can have a multiple number of such classes, they just need to have a different name
 * any elements in this class will override original elements
 */

namespace Ppb\Model\Elements\Listing;

use Ppb\Model\Elements\AbstractElements;
use Cube\Validate;

class AdvancedCustomFields extends AbstractElements
{
    /**
     *
     * related class
     *
     * @var bool
     */
    protected $_relatedClass = true;

    /**
     *
     * get model elements
     *
     * @return array
     */
    public function getElements()
    {
        $elements = array();

        $categoryId = $this->getData('category_id');
        $addlCategoryId = $this->getData('addl_category_id');
        $listingType = $this->getData('listing_type');

        $categoriesFilter = array(0);

        if ($categoryId) {
            $categoriesFilter = array_merge($categoriesFilter, array_keys(
                $this->getCategories()->getBreadcrumbs($categoryId)));
        }

        if ($addlCategoryId) {
            $categoriesFilter = array_merge($categoriesFilter, array_keys(
                $this->getCategories()->getBreadcrumbs($addlCategoryId)));
        }

        $customFieldsType = 'item';

        $customFields = $this->getCustomFields()->getFields(
            array(
                'type'         => $customFieldsType,
                'active'       => 1,
                'category_ids' => $categoriesFilter,
            ))->toArray();

        $lastAfter = null;

        foreach ($customFields as $key => $customField) {
            if (empty($customField['form_id'])) {
                $customFields[$key]['form_id'] = array($listingType, 'product_edit');
            }

            $customFields[$key]['id'] = 'custom_field_' . $customField['id'];
            $customFields[$key]['subform'] = 'details';

            if (!empty($customField['multiOptions'])) {
                $multiOptions = \Ppb\Utility::unserialize($customField['multiOptions']);

                // display only the multi options that correspond to the categories selected
                if (!empty($multiOptions['categories'])) {
                    foreach ($multiOptions['categories'] as $k => $v) {
                        $categoriesArray = array_filter((array)$v);
                        if (count($categoriesArray) > 0) {
                            $intersect = array_intersect($categoriesArray, $categoriesFilter);
                            if (count($intersect) == 0) {
                                unset($multiOptions['key'][$k]);
                                unset($multiOptions['value'][$k]);
                                unset($multiOptions['categories'][$k]);
                            }
                        }
                    }

                    unset($multiOptions['categories']);
                }

                $customFields[$key]['multiOptions'] = serialize($multiOptions);
                $customFields[$key]['bulk']['multiOptions'] = array_flip(array_filter($multiOptions['key']));
            }

            if (array_key_exists($key, $customFields)) {
                if ($customField['product_attribute']) {
                    $customFields[$key]['required'] = false;
                    $customFields[$key]['productAttribute'] = true;
                    $customFields[$key]['multiple'] = true;
                }
            }

//            if (!empty($customField['setup_form_after_id'])) {
//                $currentAfter = ($lastAfter == $customField['setup_form_after_id']) ? $customFields[$key - 1]['id'] : $customField['setup_form_after_id'];
//                $customFields[$key]['after'] = array('id', $currentAfter);
//            }
//            else {
//                $customFields[$key]['before'] = array('id', 'description');
//            }
//
//            $lastAfter = $customField['setup_form_after_id'];

            if (!empty($customField['validators'])) {
                $elementValidators = (array)\Ppb\Utility::unserialize($customField['validators']);
                $customFields[$key]['validators'] = $elementValidators;
            }
            else {
                $customFields[$key]['validators'] = array();
            }

            if (!empty($customField['greater_than'])) {
                if ($customField['element'] == '\Ppb\Form\Element\DateInputmask') {
                    array_push($customFields[$key]['validators'], new \Ppb\Validate\DateGreaterThan(array($customField['greater_than'], true)));
                }
                else {
                    array_push($customFields[$key]['validators'], new Validate\GreaterThan(array($customField['greater_than'], true)));
                }
            }

            if (!empty($customField['less_than'])) {
                if ($customField['element'] == '\Ppb\Form\Element\DateInputmask') {
                    array_push($customFields[$key]['validators'], new \Ppb\Validate\DateLessThan(array($customField['less_than'], true)));
                }
                else {
                    array_push($customFields[$key]['validators'], new Validate\LessThan(array($customField['less_than'], true)));
                }
            }

            if (!empty($customField['form_element_partial'])) {
                $customFields[$key]['partial'] = $customField['form_element_partial'];
            }

            if (!empty($customField['description'])) {
                $customFields[$key]['bulk']['notes'] = $customField['description'];
            }
        }

        $elements = $customFields;

        return $elements;
    }
}

