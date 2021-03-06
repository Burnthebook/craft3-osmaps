<?php
/**
 * OS Maps plugin for Craft CMS 3.x
 *
 * Integration with the Ordnance Survey Maps API
 *
 * @link      https://github.com/Burnthebook
 * @copyright Copyright (c) 2019 Burnthebook Ltd.
 */

namespace Burnthebook\OSMaps\assetbundles\OSMaps;

use craft\web\AssetBundle;

/**
 * OsMapsAsset AssetBundle
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Dimitar Kokov
 * @package   OSMaps
 * @since     1.0.0
 */
class OSMapsAsset extends AssetBundle
{
    /**
     * Initializes the bundle.
     */
    public function init()
    {
        $this->sourcePath = "@Burnthebook/OSMaps/assetbundles/OSMaps/resources";

        $this->js = [
            'leaflet.js',
            'proj4-compressed.js',
            'proj4leaflet.js',
            'osMaps.js'
        ];

        $this->css = [
            'leaflet.css'
        ];

        parent::init();
    }
}
