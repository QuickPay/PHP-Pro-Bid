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

namespace Ppb\Model\Elements\Search;

use Ppb\Model\Elements\AbstractElements,
    Ppb\Db\Table\Row\User as UserModel,
    Ppb\Model\Elements\CustomField;

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
     * user object (used to generate store categories)
     *
     * @var \Ppb\Db\Table\Row\User
     */
    protected $_store;

    /**
     *
     * get store user object
     *
     * @return \Ppb\Db\Table\Row\User
     */
    public function getStore()
    {
        return $this->_store;
    }

    /**
     *
     * set store user object
     *
     * @param \Ppb\Db\Table\Row\User $store
     *
     * @return $this
     */
    public function setStore(UserModel $store = null)
    {
        $this->_store = $store;

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
        $elements = array();

        $categoriesSelect = $this->getCategories()->getTable()
            ->select()
            ->where('enable_auctions = ?', 1)
            ->order(array('order_id ASC', 'name ASC'));


        if ($this->_store instanceof UserModel) {
            $categoriesSelect->where("user_id is null OR user_id = '{$this->_store['id']}'");
        }
        else {
            $categoriesSelect->where('user_id is null');
        }

        $categoriesFilter = array(0);

        if ($parentId = $this->getData('parent_id')) {
            if (in_array('advanced', $this->_formId)) {
                $categoriesSelect->where('parent_id is null');
            }
            else {
                $categoriesSelect->where('parent_id = ?', $parentId);
            }

            $categoriesFilter = array_merge($categoriesFilter, array_keys(
                $this->getCategories()->getBreadcrumbs($parentId)));
        }
        else {
            $categoriesSelect->where('parent_id is null');

            if ($this->_store instanceof UserModel) {
                $storeCategories = $this->_store->getStoreSettings('store_categories');
                if ($storeCategories != null) {
                    $categoriesSelect->where('id IN (?)', $storeCategories);
                }
            }
        }

        $customFields = $this->getCustomFields()->getFields(
            array(
                'type'         => 'item',
                'active'       => 1,
                'searchable'   => 1,
                'category_ids' => $categoriesFilter,
            ))->toArray();

        foreach ($customFields as $key => $customField) {
            if ($customField['is_range']) {
                $elementType = (in_array($customField['element'], array('select', 'radio', 'checkbox', CustomField::ELEMENT_SELECTIZE))) ?
                    '\\Ppb\\Form\\Element\\RangeSelect' : '\\Ppb\\Form\\Element\\Range';
                $elementAttributes = (array)\Ppb\Utility::unserialize($customField['attributes']);
                $elementAttributes['class'] = 'form-control input-mini';

                if ($customField['element'] == '\Ppb\Form\Element\DateInputmask') {
                    $elementAttributes['data-mask'] = '00-00-0000';
                    $elementAttributes['placeholder'] = 'dd-mm-yyyy';
                }
                if ($customField['element'] == '\Ppb\Form\Element\YearInputmask') {
                    $elementAttributes['data-mask'] = '0000';
                    $elementAttributes['placeholder'] = 'yyyy';
                }

                $elements[] = array(
                    'append'     => true,
                    'id'         => (!empty($customField['alias'])) ?
                        $customField['alias'] : 'custom_field_' . $customField['id'],
                    'element'    => $elementType,
                    'attributes' => $elementAttributes,

                );
            }
        }

        return $elements;
    }
}

