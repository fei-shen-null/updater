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
 * Curl x64 - Version Crawler
 *
 * Website: https://curl.haxx.se/
 * Github:  https://github.com/curl/curl
 *
 * Builds by Viktor Szakats
 * Github:               https://github.com/vszakats/harbour-deps
 * Downloads on Bintray: https://bintray.com/vszakats/generic/curl/#files
 */
class curl_x86 extends VersionCrawler
{
    public $name = 'curl-x86';

    // we could scrape https://bintray.com/vszakats/generic/curl/#files
    // but bintray is cool and provides an API including "latest_version", which is superb!
    public $url = 'https://api.bintray.com/packages/vszakats/generic/curl';

    public function crawlVersion()
    {
        $version = json_decode(file_get_contents($this->url), true)['latest_version'];

        /**
         * Downloads are on Bintray
         *
         * API for Downloading Content:
         * https://bintray.com/docs/api/#_download_content
         *
         * DL URL:         *
         * https://dl.bintray.com/vszakats/generic/curl-7.47.1-win32-mingw-libressl.7z
         */
        $download_file = 'https://dl.bintray.com/vszakats/generic/curl-' . $version . '-win32-mingw-libressl.7z';

        if (version_compare($version, $this->registry['curl-x86']['latest']['version'], '>=') === true) {
            return array(
                'version' => $version,
                'url'     => $download_file,
            );
        }
    }
}
