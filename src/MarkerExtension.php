<?php


namespace A2nt\SilverStripeMapboxField;


use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use Symbiote\Addressable\Geocodable;

class MarkerExtension extends Geocodable
{
    public function updateCMSFields(FieldList $fields)
    {
        parent::updateCMSFields($fields);

        $record = $this->getOwner();

        $fields->removeByName(['LatLngOverride', 'Lng','Lat']);
        $fields->addFieldsToTab('Root.Map', [
            CheckboxField::create('LatLngOverride', 'Override Latitude and Longitude?')
                ->setDescription('Check this box and save to be able to edit the latitude and longitude manually.'),
            MapboxField::create('Map', 'Choose a location', 'Lat', 'Lng'),
        ]);
    }

    public function getDirectionsURL()
    {
        $obj = $this->owner;
        return 'https://www.google.com/maps/dir/Current+Location/'
            .$obj->getField('Lat').',' .$obj->getField('Lng');
    }

    public function forTemplate()
    {
        $obj = $this->owner;
        $class =get_class($obj);

        return $obj->renderWith($class);
    }
}
