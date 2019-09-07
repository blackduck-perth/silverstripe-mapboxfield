<?php


namespace A2nt\SilverStripeMapboxField;

use SilverStripe\Core\Extension;

class SiteConfigExtension extends Extension
{
    public function MapAPIKey()
    {
        return MapboxField::getAccessToken();
    }

    public function MapStyle()
    {
        return MapboxField::config()->get('map_style');
    }
}
