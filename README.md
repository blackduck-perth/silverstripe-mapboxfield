# SilverStripe Mapbox Field

Extends:
   - Symbiote\Addressable\Geocodable

Replaces:
   - dynamic/silverstripe-elemental-customer-service
   - bigfork/silverstripe-mapboxfield

Adds a Mapbox map to the CMS with a draggable marker to allow content authors to add a location to a DataObject or Page.

<img src="docs/img/cms.png" alt="" />

Address will be geocoded automatically or manually using mapbox map.

Adds DataObject Extension to store and render GeoJSON data.

## Installation

`composer require a2nt/silverstripe-mapboxfield:*`

## Configuration

```yaml

---
Name: 'app-map'
After:
  - 'silverstripe-mapboxfield'
  - 'addressable'
---
SilverStripe\Core\Injector\Injector:
  A2nt\SilverStripeMapboxField\MarkerExtension:
    properties:
      geocoder: %$Symbiote\Addressable\MapboxGeocodeService
  Symbiote\Addressable\GeocodeServiceInterface:
    class: Symbiote\Addressable\MapboxGeocodeService

Symbiote\Addressable\MapboxGeocodeService:
  mapbox_api_key: 'your mapbox access token'
A2nt\SilverStripeMapboxField\MapboxField:
  map_style: 'mapbox://styles/mapbox/light-v10'

```

## Usage

```php

class MyDataObjectMapPin extends DataObject
{
    private static $extensions = [
        Addressable::class,
        MarkerExtension::class,
    ];
}
```

## Example
```php
class MyPage extends \Page
{
    private static $db = [
        'MapZoom' => 'Int',
    ];
    
    public function MapPins()
    {
        return MyDataObjectMapPin::get();
    }

    public function getGeoJSON()
    {
        $pins = [];
        foreach ($this->MapPins() as $pin) {
            $pins[] = $pin->getGeo();
        }
        return json_encode([
            'type' => 'MarkerCollection',
            'features' => $pins
        ]);
    }
}
```

create MyDataObjectMapPin.ss template to render popup content

MyPage.ss
```html
<div
	class="mapAPI-map-container"
	data-map-zoom="$MapZoom"
	data-key="<% if $MapAPIKey %>$MapAPIKey<% else %>$SiteConfig.MapAPIKey<% end_if %>"
	data-map-style="<% if $MapStyle %>$MapStyle<% else %>$SiteConfig.MapStyle<% end_if %>"
	data-geojson="$GeoJSON.XML"
>
    <div class="mapAPI-map"></div>
</div>
```

Process it with javascript on frontend, you can find an example at: https://github.com/a2nt/webpack-bootstrap-ui-kit/blob/master/src/js/_components/_ui.map.api.js
