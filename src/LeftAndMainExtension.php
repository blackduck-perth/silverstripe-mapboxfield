<?php

namespace A2nt\SilverStripeMapboxField;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;
use Symbiote\Addressable\MapboxGeocodeService;

class LeftAndMainExtension extends Extension
{
    public function onAfterInit()
    {
        $config = MapboxField::config();
        $token = MapboxField::getAccessToken();

        Requirements::css($config->api_css_url);
        Requirements::javascript($config->api_javascript_url);
        Requirements::css($config->geocoder_css_url);
        Requirements::javascript($config->geocoder_javascript_url);
        Requirements::customScript('window.mapboxAccessToken = \''.$token.'\';');
    }
}
