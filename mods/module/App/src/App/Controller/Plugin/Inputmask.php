<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2014 Online Ventures Software LTD & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     7.0
 */
/**
 * inputmask jquery plugin initialize
 */
/**
 * MOD:- ADVANCED CUSTOM FIELDS
 */

namespace App\Controller\Plugin;

use Cube\Controller\Plugin\AbstractPlugin,
    Cube\Controller\Front;

class Inputmask extends AbstractPlugin
{


    /**
     *
     * we get the view and initialize the css and js for the plugin
     */
    public function preDispatch()
    {
        $view = Front::getInstance()->getBootstrap()->getResource('view');

        $baseUrl = $this->getRequest()->getBaseUrl();

        /** @var \Cube\View\Helper\Script $scriptHelper */
        $scriptHelper = $view->getHelper('script');
        $scriptHelper
//            ->addBodyCode('<script type="text/javascript" src="' . $baseUrl . '/mods/js/jquery.inputmask.bundle.min.js"></script>')
            ->addBodyCode('<script type="text/javascript" src="' . $baseUrl . '/mods/js/jquery.mask.min.js"></script>');
//            ->addBodyCode('<script type="text/javascript">
//                $(document).ready(function(){
//                    $(":input").inputmask();
//                });
//            </script>');

    }

}

