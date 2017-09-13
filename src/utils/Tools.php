<?php
namespace Harps\Utils;

class Tools
{
    /**
     * Generate a new GUID
     * @return string
     */
    public static function GUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Change all '/', '\\' or '\' of a string to a DIRECTORY_SEPARATOR
     * @param string $path Path string to change
     * @return string
     */
    public static function to_ds(string $path) : string
    {
        return str_replace(array('/', '\\\\', '\\'), DIRECTORY_SEPARATOR, $path);
    }
}
