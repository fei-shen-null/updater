<?php

/**
 * WPИ-XM Server Stack - Updater
 * Copyright © 2010 - 2015 Jens-André Koch <jakoch@web.de>
 * http://wpn-xm.org/
 *
 * This source file is subject to the terms of the MIT license.
 * For full copyright and license information, view the bundled LICENSE file.
 */

namespace WPNXM\Updater\Action;

use WPNXM\Updater\ActionBase;
use WPNXM\Updater\ArrayUtil;
use WPNXM\Updater\Registry;
use WPNXM\Updater\StatusRequest;
use WPNXM\Updater\View;

/**
 * LinkStatus for all links of a Component.
 */
class LinkStatusComponent extends ActionBase
{
    private $registry = array();

	public function __construct()
    {
    	if (!extension_loaded('curl')) {
            exit('Error: PHP Extension cURL required.');
        }

        $this->registry = Registry::load();
    }

    public function __invoke()
    {
    	$software = filter_var($_GET['software'], FILTER_SANITIZE_STRING);
    	$componentArray = ArrayUtil::reduceArrayToContainOnlyVersions($this->registry[$software]);

		$before       = microtime(true);
        $urls         = array_values($componentArray);
        $responses    = StatusRequest::getHttpStatusCodesInParallel($urls);
        $crawlingTime = round((microtime(true) - $before), 2);

        // build a lookup array with the relation of "url" => "http status code" (true, false)
        $urlsHttpStatus = array_combine($urls, $responses);

        // define a closure (as viewhelper) for the lookup (inherit array by-reference)
        $isAvailable = function($url) use(&$urlsHttpStatus) {
            return $urlsHttpStatus[$url];
        };

		$view = new View();
		$view->data['component']      = $software;
        $view->data['before']         = $before;
        $view->data['crawlingTime']   = $crawlingTime;
        $view->data['isAvailable']    = $isAvailable;
        $view->data['urlsHttpStatus'] = $urlsHttpStatus;
        $view->data['numberOfUrls']   = count($urls);
        $view->render();
    }
}