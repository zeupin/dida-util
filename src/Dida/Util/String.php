<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files MUST retain the above copyright notice.
 */

namespace Dida\Util;

class String
{
    const VERSION = '20171115';


    public static function matchPrefix($str, $prefixes)
    {
        if ($prefixes === null || $prefixes === '') {
            return true;
        }

        if (is_string($prefixes)) {
            $len = mb_strlen($prefixes);
            return (mb_substr($str, 0, $len) === $prefixes);
        }

        if (is_array($prefixes)) {
            foreach ($prefixes as $prefix) {
                if (is_string($prefix)) {
                    $len = mb_strlen($prefix);
                    if (mb_substr($str, 0, $len) === $prefix) return true;
                }
            }
            return false;
        }

        return false;
    }


    public static function matchSuffix($str, $suffixes)
    {
        if ($suffixes === null || $suffixes === '') {
            return true;
        }

        if (is_string($suffixes)) {
            $len = mb_strlen($suffixes);
            return (mb_substr($str, -$len) === $suffixes);
        }

        if (is_array($suffixes)) {
            foreach ($suffixes as $suffix) {
                if (is_string($suffix)) {
                    $len = mb_strlen($suffix);
                    if (mb_substr($str, -$len) === $suffix) {
                        return true;
                    }
                }
            }
            return false;
        }

        return false;
    }
}
