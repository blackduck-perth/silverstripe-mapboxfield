<?php


namespace A2nt\SilverStripeMapboxField;

use SilverStripe\Core\Extension;

class SiteConfigExtension extends Extension
{
    public function MapAPIKey()
    {
        return MapboxField::getAccessToken();
    }
    
     public static function MapAPIKeyFrontend(): string
    {
        $type = $this->owner->config()->get('map_type');

        switch ($type) {
            case 'mapbox':
                $key = MapboxField::getAccessToken();
                break;
            case 'google-maps':
                $cfg = Config::inst()->get(GoogleMapField::class, 'default_options');
                $key = $cfg['api_key'];
                break;
            default:
                $key = '';
                break;
        }

        return $key;
    }

    public function MapStyle()
    {
        return MapboxField::config()->get('map_style');
    }
}
