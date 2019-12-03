# OS Maps plugin for Craft CMS 3.x

Allows you to display Ordnance Survey maps on your Craft CMS website.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

       cd /path/to/project

2. Then tell Composer to load the plugin:

       composer require devkokov/craft3-osmaps

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for OS Maps.

## Configuration

1. Add your OS Maps API Key on the plugin's settings page in the Control Panel.

2. Set a Max Zoom Level value. This is usually 10 if you are not allowed to display the OS MasterMap Topography Layer.

## Using OS Maps 

Add the following to your twig template and modify where necessary.

```twig
{% do view.registerAssetBundle("DevKokov\\OSMaps\\assetbundles\\OSMaps\\OSMapsAsset") %}

{% set tileUrl = craft.osMaps.getApiUrl() %}
{% set maxZoomLevel = craft.osMaps.getMaxZoomLevel() %}
{% set zoomLevel = 10 %}
{% set lat = 52.921309 %}
{% set long = -1.475118 %}

<div id="map" style="height: 500px; width: 500px;"></div>

{% js %}
    var map = createOSMap('map', '{{ tileUrl }}', { maxZoom: {{ maxZoomLevel }} });
    var latlng = [{{ lat }}, {{ long }}];
   
    map.setView(latlng, {{ zoomLevel }});
    L.marker(latlng).addTo(map);
{% endjs %}
```

See the [Leaflet Documentation](https://leafletjs.com/reference-1.0.3.html) for reference on using the `L` JavaScript object.

Note that the `createOSMap()` function returns a Leaflet Map object.

## Advanced usage

The `getApiUrl()` method accepts an object with options.

See the [OS Maps Documentation](https://apidocs.os.uk/docs/os-maps-wmts) for more details.

The default options we define are:

```twig
{% set tileUrl = craft.osMaps.getApiUrl({
    'service': 'WMTS',
    'request': 'GetTile',
    'version': '1.0.0',
    'layer': 'Road 27700',
    'style': 'true',
    'format': 'image/png',
    'tileMatrixSet': 'EPSG:27700',
    'tileMatrix': 'EPSG:27700:{z}',
    'tileRow': '{y}',
    'tileCol': '{x}'
}) %}
```

If you are using Google Maps in the Control Panel (e.g. Maps plugin), you will want to convert the Google Maps zoom level to an OS Maps zoom level:

```twig
{% set zoomLevel = craft.osMaps.convertGMapsZoomLevel(entry.map.zoom) %}
```

The JavaScript function `createOSMap()` accepts the following parameters:

- id : ID of the Map's HTML element
- tileUrl : URL to fetch tiles from
- tileLayerOptions : Options for the Leaflet TileLayer object. See the Leaflet documentation for more details.
- mapOptions : Options for the Leaflet Map object. See the Leaflet documentation for more details.
- crs : An optional CRS object. See the Leaflet and/or Proj4leaflet documentations for instructions on creating a CRS object. It's fairly complicated. We provide a default CRS object for EPSG:27700

The function returns a standard Leaflet Map object. Do with it as you wish.

The Leaflet library (`L` object in JS) is also globally exposed should you wish to use it e.g. for adding markers or manipulating the map.

## Useful resources

- 

---

Brought to you by [Dimitar Kokov](https://github.com/devkokov)