<?php
/**
 * OS Maps plugin for Craft CMS 3.x
 *
 * Integration with the Ordnance Survey Maps API
 *
 * @link      https://github.com/devkokov
 * @copyright Copyright (c) 2019 Dimitar Kokov
 */

namespace DevKokov\OSMaps\Services;

use craft\base\Component;
use DevKokov\OSMaps\OSMaps;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Proxy\Filter\RemoveEncodingFilter;
use Proxy\Proxy;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * OsMapsService Service
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Dimitar Kokov
 * @package   OSMaps
 * @since     1.0.0
 */
class OSMapsService extends Component
{
    const WMTS_URL = 'https://api2.ordnancesurvey.co.uk/mapping_api/v1/service/wmts';

    /**
     * @return ResponseInterface
     */
    public function routeWmts()
    {
        $guzzle = new Client();
        $proxy = new Proxy(new GuzzleAdapter($guzzle));
        $proxy->filter(new RemoveEncodingFilter());

        $currentRequest = ServerRequestFactory::fromGlobals();

        $uri = (new Uri())->withQuery($currentRequest->getUri()->getQuery());
        $uri = Uri::withoutQueryValue($uri, 'p');
        $uri = Uri::withQueryValue($uri, 'key', OSMaps::$plugin->getSettings()->apiKey);

        // manipulate query params before forwarding to OS Maps API

        $maxZoomLevel = OSMaps::$plugin->getSettings()->maxZoomLevel;

        parse_str($uri->getQuery(), $params);
        foreach ($params as $param => $value) {
            switch (strtolower($param)) {
                case 'tilematrix':
                    // restrict Max Zoom Level
                    $parts = explode(':', $value);
                    if (isset($parts[2]) && is_numeric($parts[2]) && $parts[2] > $maxZoomLevel) {
                        $uri = Uri::withQueryValue($uri, $param, $maxZoomLevel);
                    }
                    break;
            }
        }

        $request = new Request($currentRequest->getMethod(), $uri);

        try {
            $response = $proxy->forward($request)->to(self::WMTS_URL);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}