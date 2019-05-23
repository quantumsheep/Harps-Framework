<?php
namespace Harps\FilesUtils;

use Harps\Utils\Tools;

class Files
{
    /**
     * Create a file
     * @param string $path File path
     * @param string $content File content
     */
    protected static function createOne(string $path, string $content = null): bool
    {
        if (file_put_contents($path, $content) === false) {
            return false;
        }

        return true;
    }

    /**
     * Delete a file
     * @param string $file File path
     * @return boolean
     */
    protected static function deleteOne(string $file): bool
    {
        if (file_exists($file)) {
            if (!unlink($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if direcoty(ies) exists and creates it if not.
     * @param string|array $files Path to the file(s). To create multiple files use array with key as path and value as content, if no key is defined so just set the path and the content will be null.
     * @param string $content File content
     * @return boolean
     */
    public static function create($files, string $content = null): bool
    {
        $return = true;

        if (is_array($files)) {
            foreach ($files as $key => $value) {
                if (is_string($value) || is_string($key)) {
                    self::createOne($key, (is_string($value) ? $value : null));
                } else {
                    self::createOne($value, $content);
                }
            }
        } else {
            self::createOne($files, $content);
        }

        return $return;
    }

    /**
     * Delete given file(s)
     * @param array|string $files Path to the file(s).
     * @return boolean
     */
    public static function delete($files): bool
    {
        $return = true;

        if (is_array($files)) {
            foreach ($files as $file) {
                if (!self::deleteOne($file)) {
                    $return = false;
                }
            }
        } else {
            return self::deleteOne($files);
        }

        return $return;
    }

    /**
     * * Read given file(s)
     * @param array|string $files Path to the file(s).
     * @return \bool|string
     */
    public static function read($files)
    {
        if (is_array($files)) {
            $return = array();

            foreach ($files as $file) {
                $file = Tools::to_ds($file);

                if (file_exists($file)) {
                    $return[$file] = file_get_contents($file);
                }
            }

            if ($return != null && count($return) < 1) {
                return false;
            } else {
                return $return;
            }
        } else {
            if (file_exists($files)) {
                return file_get_contents($files);
            } else {
                return false;
            }
        }
    }
}
