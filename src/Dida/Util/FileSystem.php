<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files MUST retain the above copyright notice.
 */

namespace Dida\Util;

class FileSystem
{
    const VERSION = '20200530';

    public static function delDir($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $file ;

            if (is_dir($path)) {
                $result = self::delDir($path);
            } else {
                $result = unlink($path);
            }
            if ($result === false) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static function getFiles($dir, $extensions = null, $ignores = null)
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            return [];
        }

        if ($ignores === null) {
        } elseif (is_string($ignores)) {
            $ignores = explode(',', $ignores);
            foreach ($ignores as $key => $item) {
                $ignores[$key] = trim($item);
            }
        } elseif (is_array($ignores)) {
            foreach ($ignores as $key => $item) {
                $ignores[$key] = trim($item);
            }
        } else {
            throw new Exception('Invalid argument type $ignores.');
        }

        if ($extensions === null) {
        } elseif (is_string($extensions)) {
            $extensions = explode(',', $extensions);
            foreach ($extensions as $key => $item) {
                $item = trim($item);
                $extensions[$key] = ['ext' => $item, 'len' => mb_strlen($item)];
            }
        } elseif (is_array($extensions)) {
            foreach ($extensions as $key => $item) {
                $item = trim($item);
                $extensions[$key] = ['ext' => $item, 'len' => mb_strlen($item)];
            }
        } else {
            throw new Exception('Invalid argument type $extensions.');
        }

        $ret = [];

        $todo = [];
        $todo[] = realpath($dir);

        while (true) {
            $folder = array_shift($todo);

            $subfolders = [];

            $files = scandir($folder);

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_array($ignores)) {
                    if (in_array($file, $ignores)) {
                        continue;
                    }
                }

                $path = $folder . DIRECTORY_SEPARATOR . $file;

                if (is_dir($path)) {
                    $subfolders[] = $path;
                    continue;
                }

                if ($extensions === null) {
                    $ret[] = $path;
                } elseif (is_array($extensions)) {
                    foreach ($extensions as $ext) {
                        if (mb_substr($file, -$ext['len']) === $ext['ext']) {
                            $ret[] = $path;
                        }
                    }
                }
            }

            if ($subfolders) {
                $todo = array_merge($subfolders, $todo);
            }

            if (!$todo) {
                break;
            }
        }

        return $ret;
    }
}
