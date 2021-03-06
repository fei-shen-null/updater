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
 * Version Crawler for
 *
 * RoboMongo - A Shell-centric cross-platform MongoDB management tool.
 *
 * Website:   http://robomongo.org/
 * Downloads: http://download.robomongo.org/
 *            http://app.robomongo.org/download.html
 *            formerly http://robomongo.org/download.html
 */
class robomongo extends VersionCrawler
{
    public $url = 'http://app.robomongo.org/download.html';

    public function crawlVersion()
    {
        return $this->filter('table a')->each(function ($node) {
            // http://download.robomongo.org/0.9.0-rc4/windows/robomongo-0.9.0-rc4-windows-x86_64-8c830b6.exe
            if (preg_match("#robomongo-((\d+\.\d+.\d+)(?:-rc\d))-windows-x86_64-(.*)\.exe#i", $node->attr('href'), $matches)) {
                $version = $matches[1];
                $hash    = $matches[3]; // why did you add a hash?
                if (version_compare($version, $this->registry['robomongo']['latest']['version'], '>=') === true) {
                    return array(
                        'version' => $version,
                        'url' => 'http://download.robomongo.org/' . $version . '/windows/robomongo-' . $version . '-windows-x86_64-' . $hash . '.exe',
                    );
                }
            }
        });
    }
}
