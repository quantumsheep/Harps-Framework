<?php
namespace Harps\Client;

class Assets {
    /**
     * Undocumented function
     * @param array|string $assets 
     */
    public static function define($assets) {
        if(\is_array($assets)) {
            foreach($assets as $asset) {
                $GLOBALS["ASSETS"][$assets] = ASSET_ACCEPTED;
            }
        } else {
            $GLOBALS["ASSETS"][$assets] = ASSET_ACCEPTED;
        }
        
        return $this;
    }

    public static function load() {
        
    }
}