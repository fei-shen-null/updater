<?php

/**
 * WPИ-XM Server Stack - Updater
 * Copyright © 2010 - 2015 Jens-André Koch <jakoch@web.de>
 * http://wpn-xm.org/
 *
 * This source file is subject to the terms of the MIT license.
 * For full copyright and license information, view the bundled LICENSE file.
 */

namespace WPNXM\Updater;

class Version
{
    /**
     * Welcome in Version Compare Hell!
     * Some software components need their own version compare handling.
     */
    public static function compare($component, $oldVersion, $newVersion)
    {
        switch ($component) {
            case 'openssl':
            case 'openssl-x64':
                if (strcmp($oldVersion, $newVersion) < 0) {
                    return true;
                }
            case 'phpmyadmin':
                if (version_compare($oldVersion, $newVersion, '<') === true || (strcmp($oldVersion, $newVersion) < 0)) {
                    return true;
                }
            case 'imagick':
                if (Version::cmpImagick($oldVersion, $newVersion) === true) {
                    return true;
                }
            default:
                if (version_compare($oldVersion, $newVersion, '<') === true) {
                    return true;
                }
        }

        return false;
    }

    /**
     * Compare an Imagick version number.
     *
     * @param  string  $oldVersion
     * @param  string  $newVersion
     * @return boolean True, if newVersion is higher then oldVersion.
     */
    public static function cmpImagick($oldVersion, $newVersion)
    {
        $rOldVersion = str_replace('-', '.', $oldVersion);
        $rNewVersion = str_replace('-', '.', $newVersion);

        return version_compare($rNewVersion, $rOldVersion, '>');
    }

    /**
     * @return string
     */
    public static function notInRegistry($version, $registry, $returnVersion = false)
    {
        // check registry, using one version to url relationship
        if(is_array($version) && count($version) === 2) {
            if(isset($registry[$version['version']]) === false) {
                return ($returnVersion === true) ? $version['version'] : true;
            }
        }

        // check registry, using multiple version to url relationships
        if(is_array($version) && count($version) > 2) {
            // if one out of multiple version is missing.. return true.
            foreach($version as $v) {
                if(isset($registry[$v['version']]) === false) {
                    return ($returnVersion === true) ? $v['version'] : true;
                }
            }
        }

        // check registry, using just a version number
        if(!is_array($version) && !isset($version['version']) && isset($registry[$version]) === false) {
            return ($returnVersion === true) ? $version : true;
        }
    }

    /**
     * Sorts an array by version.
     *
     * @param  array  $array versions
     * @return array  sorted versions
     */
    public static function sortByVersion(array $array)
    {
        $sort = function ($versionA, $versionB) {
            return version_compare($versionA['version'], $versionB['version']);
        };
        usort($array, $sort);

        return $array;
    }
}