<?php

/**
 * WPИ-XM Server Stack - Updater
 * Copyright © 2010 - 2015 Jens-André Koch <jakoch@web.de>
 * http://wpn-xm.org/
 *
 * This source file is subject to the terms of the MIT license.
 * For full copyright and license information, view the bundled LICENSE file.
 */

namespace WPNXM\Updater\Crawler;

use WPNXM\Updater\VersionCrawler;

/**
 * Selenium - Version Crawler
 * 
 * Selenium automates browsers.
 * 
 * Website:         http://www.seleniumhq.org/
 * Downloads:       http://www.seleniumhq.org/download/
 * Latest Releases: http://selenium-release.storage.googleapis.com/index.html
 */
class Selenium extends VersionCrawler
{
    public $name = 'selenium';
  
    public $url = 'http://www.seleniumhq.org/download/';

    public function crawlVersion()
    { 
        return $this->filterXPath('//*[@id="mainContent"]/p[3]')->each(function ($node) {
            if (preg_match("#Download version (\d+\.\d+(.\d+)*)#i", $node->text(), $matches)) {
                $version = $matches[1];
                // find last dot and return everything before
                $version_withoutPatchLevel = substr($version, 0, strripos($version, '.'));
                if (version_compare($version, $this->registry['selenium']['latest']['version'], '>=') === true) {
                    return array(
                        'version' => $version,
                        'url'     => 'http://selenium-release.storage.googleapis.com/'.$version_withoutPatchLevel.'/selenium-server-standalone-' . $version . '.jar'
                    );
                }
            }
        });
    }

}
