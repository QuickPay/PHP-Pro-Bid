<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2018 Online Ventures Software & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     8.0 [rev.8.0.05]
 */

/**
 * display custom fields view helper class
 */
/**
 * MOD:- ADVANCED CUSTOM FIELDS
 */
 
namespace Listings\View\Helper;

use Ppb\View\Helper\AbstractHelper,
    Cube\Controller\Front,
    Cube\Paginator,
    Ppb\Model\Elements\Search as SearchElementsModel,
    Ppb\Form\Element\Range,
    Ppb\Service;

class DisplayCustomFields extends AbstractHelper
{

    const DEFAULT_PARTIAL = 'partials/display-custom-fields.phtml';
    /**
     *
     * the view partial to be used
     *
     * @var string
     */
    protected $_partial = self::DEFAULT_PARTIAL;

    /**
     *
     * display box id
     *
     * @var int|null
     */
    protected $_displayBoxId = null;

    /**
     *
     * listing model
     *
     * @var \Ppb\Db\Table\Row\Listing
     */
    protected $_listing;

    /**
     * @return \Ppb\Db\Table\Row\Listing
     */
    public function getListing()
    {
        return $this->_listing;
    }

    /**
     *
     * @param \Ppb\Db\Table\Row\Listing $listing
     *
     * @return $this
     */
    public function setListing($listing)
    {
        $this->_listing = $listing;

        return $this;
    }

    /**
     *
     * display custom fields view helper
     *
     * @param int  $displayBoxId
     * @param string $partial
     *
     * @return $this
     */
    public function displayCustomFields($displayBoxId = null, $partial = self::DEFAULT_PARTIAL)
    {
        $this->_displayBoxId = $displayBoxId;

        $this->setPartial($partial);

        return $this;
    }

    /**
     *
     * render partial
     *
     * @return string
     */
    public function render()
    {
        $view = $this->getView();

        $view->setVariables(array(
            'listing'      => $this->getListing(),
            'displayBoxId' => $this->_displayBoxId,
        ));

        return $view->process(
            $this->getPartial(), true);
    }
}

