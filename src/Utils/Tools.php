<?php
namespace Harps\Utils;

class Tools
{
    /**
     * Generate a new GUID
     *
     * @return string
     */
    public static function guid_gen(): string
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Check the validity of a GUID
     *
     * @param string $guid The guid to check
     * @return bool
     */
    public static function guid_check(string $guid): bool
    {
        if (preg_match('/^\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/', $guid)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Change all '/', '\\' or '\' of a string to a DIRECTORY_SEPARATOR
     *
     * @param string $path Path string to change
     * @return string
     */
    public static function to_ds(string $path): string
    {
        return str_replace(array('/', '\\\\', '\\'), DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Undocumented function
     *
     * @param array $delimiters The delimiters
     * @param string $string The string to split
     * @return array
     */
    public static function multi_explode(array $delimiters, string $string): array
    {
        return explode($delimiters[0], str_replace($delimiters, $delimiters[0], $string));
    }
}
