<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2018 Online Ventures Software & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     8.0 [rev.8.0.02]
 */

/**
 * MOD:- ADVANCED CUSTOM FIELDS
 */

namespace Ppb\Form\Element;

use Ppb\Form\Element\MultiUpload as MultiUploadElement;

class Media extends MultiUploadElement
{

    /**
     *
     * class constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $translate = $this->getTranslate();

        $this->setCustomData(array(
            'buttonText'      => $translate->_('Select Media'),
            'acceptFileTypes' => '/(\.|\/)(mov|mp4|flv)$/i',
//            'embeddedCode'    => true,
            'formData'        => array(
                'fileSizeLimit' => 50000000, // approx 50 MB
                'uploadLimit'   => 1,
            ),
        ));

    }

}

