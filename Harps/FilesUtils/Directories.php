<?php
namespace Harps\FilesUtils;

use Harps\FilesUtils\Files;

class Directories
{
    /**
     * Delete a directory
     * @param string $directory Directory path
     * @return boolean
     */
    private static function deleteOne(string $directory) : bool {
        $return = true;
        $dir_content = scandir($directory);

        if($dir_content !== FALSE) {
            foreach($dir_content as $doc){
                if(!in_array($doc, [ '.', '..' ])) {
                    $doc = $directory . '/' . $doc;
                    if(!is_dir($doc)){
                        if(!Files::delete($doc)) {
                            $return = false;
                        }
                    } else {
                        self::delete($doc);
                    }
                }
            }
        }

        if($return !== false) {
            rmdir($directory);
        }

        return $return;
    }

    /**
     * Create a directory
     * @param string $directory Directory path
     * @param int $chmod Chmod value
     * @return boolean
     */
    private static function createOne(string $directory, int $chmod = 0770) : bool {
        if(!file_exists($directory)) {
            $path = preg_split('/(\\\\|\/)/', $directory);

            for($i = 0; $i < count($path); $i++) {
                if(!file_exists($path[$i])) {
                    if(!mkdir($path[$i], $chmod)) {
                        return false;
                    }
                }

                if($i < count($path) - 1) {
                    $path[$i + 1] = $path[$i] . DS . $path[$i + 1];
                }
            }
        }

        return true;
    }

    /**
     * Delete given directoy(ies)
     * @param array|string $directory Path(s) to the directory(ies)
     * @return boolean
     */
    public static function delete($directories) : bool {
        $return = true;

        if(is_array($directories)) {
            foreach($directories as $directory) {
                if(!self::deleteOne($directory)) {
                    $return = false;
                }
            }
        } else {
            return self::deleteOne($directories);
        }

        return $return;
    }

    /**
     * Check if direcoty(ies) exists and creates it if not.
     * @param array|string $directories Path to the directory(ies). To choose chmod per directory use array key as path and value as chmod (chmod 0770 by default if not defined for a directory).
     * @param int $chmod Chmod value for the directory
     * @return boolean
     */
    public static function create($directories, int $chmod = 0770) : bool {
        $return = true;

        if(is_array($directories)) {
            foreach($directories as $key => $value) {
                if(strlen($value) == 4 || is_string($key)) {
                    self::createOne($key, (strlen($value) == 4 ? $value : 0770));
                } else {
                    self::createOne($value);
                }
            }
        } else {
            self::createOne($directories, $chmod);
        }

        return $return;
    }
}