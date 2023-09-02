<?php


namespace A2nt\SilverStripeMapboxField;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use Symbiote\Addressable\Geocodable;
use Symbiote\Addressable\Addressable;

class MarkerExtension extends Geocodable
{
    private static $icon = '<i class="fas fa-map-marker-alt"></i>';
    private $curr_icon = null;

    private static $db = [
        'DirectionsByAddress' => 'Boolean(0)',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        parent::updateCMSFields($fields);

        $record = $this->getOwner();

        $fields->removeByName(['DirectionsByAddress', 'LatLngOverride', 'Lng', 'Lat']);


        if ($this->owner->hasExtension(Addressable::class)) {
            $fields->addFieldsToTab('Root.Map', [
                CheckboxField::create('DirectionsByAddress', 'Directions by address')
                    ->setDescription('Check this box to link directions by address')
            ]);
        }

        $fields->addFieldsToTab('Root.Map', [
            CheckboxField::create('LatLngOverride', 'Override Latitude and Longitude?')
                ->setDescription('Check this box and save to be able to set the latitude and longitude manually.'),
            ]);
			
			#Show map, or coordinates if override is ticked.
            if ($record->LatLngOverride) {
                $fields->addFieldsToTab('Root.Map', [
                    TextField::create('Lat', 'Latitude'),
                    TextField::create('Lng', 'Longitude'),
                ]);
            }
            else {
                $fields->addFieldsToTab('Root.Map', [
                    MapboxField::create('Map', 'Choose a location', 'Lat', 'Lng'),
                ]);
            }
    }

    public function getDirectionsURL()
    {
        $obj = $this->owner;
        return 'https://www.google.com/maps/dir/Current+Location/'
            .(
                $obj->getField('DirectionsByAddress')
                ? urlencode(
                    $obj->getField('Address').', '.$obj->getField('Suburb')
                    .', '.$obj->getField('State').' '.$obj->getField('Postcode')
                    .', '.$obj->getField('Country')
                )
                : $obj->getField('Lat').',' .$obj->getField('Lng')
            );
    }

    public function getIcon()
    {
        $obj = $this->owner;
        $class = get_class($obj);
        return $this->curr_icon ?: $class::config()->get('icon');
    }

    public function setIcon($icon)
    {
        $this->curr_icon = $icon;
        return $this;
    }

    public function getGeo()
    {
        $obj = $this->owner;

        return [
            'id' => $obj->ID,
            'type' => 'Feature',
            'icon' => $obj->getIcon(),
            'properties' => [
                'content' => $obj->forTemplate()->RAW(),
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $obj->getField('Lng'),
                    $obj->getField('Lat')
                ],
            ],
        ];
    }

    public function forTemplate()
    {
        $obj = $this->owner;
        $class =get_class($obj);

        return $obj->renderWith($class);
    }
}
