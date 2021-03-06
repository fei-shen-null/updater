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
 * Adminer - Version Crawler
 */
class Adminer extends VersionCrawler
{
    public $url = 'http://www.adminer.org/#download';

    public function crawlVersion()
    {
        return $this->filter('a')->each(function ($node) {
            if (preg_match("#(\d+\.\d+(\.\d+)*)#", $node->text(), $matches)) {
                $version = $matches[0];

                if (version_compare($version, $this->registry['adminer']['latest']['version'], '>=') === true) {
                    return array(
                        'version' => $version,
                        'url'     => 'https://www.adminer.org/static/download/' . $version . '/adminer-' . $version . '.php',
                    );
                }
           }
        });
    }

}
