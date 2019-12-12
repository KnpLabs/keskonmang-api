<?php

namespace App\Symfony\Filter;

use Symfony\Component\HttpFoundation\Request;
use App\Foursquare\FoursquareFilter;

class Venue implements FoursquareFilter
{
    /** @var double */
    public $latitude;

    /** @var double */
    public $longitude;

    public function __construct(Request $request) {
        $this->latitude = $request->query->get('latitude') !== null
            ? (double) $request->query->get('latitude')
            : null
        ;
        $this->longitude =$request->query->get('longitude') !== null
            ? (double) $request->query->get('longitude')
            : null
        ;

        if (
            !is_double($this->latitude) ||
            !is_double($this->longitude)
        ) {
            throw new \Exception('Latitude and longitude are mandatory parameters.');
        }
    }

    public function toQueryParameters(): string
    {
        return \sprintf('&ll=%s,%s', $this->latitude, $this->longitude);
    }
}
