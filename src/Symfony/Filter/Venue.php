<?php

namespace App\Symfony\Filter;

use Symfony\Component\HttpFoundation\Request;
use App\Foursquare\FoursquareFilter;

class Venue implements FoursquareFilter
{
    // @see https://developer.foursquare.com/docs/resources/categories
    const SUPPORTED_CATEGORIES = [
        '4d4b7105d754a06374d81259' // Nourriture
    ];

    /** @var double */
    public $latitude;

    /** @var double */
    public $longitude;

    /** @var array an array of category ids */
    public $categories;

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

        $this->categories = [];

        foreach (\explode(',', $request->query->get('categories')) as $category) {
            if (\in_array($category, self::SUPPORTED_CATEGORIES)) {
                $this->categories[] = $category;
            }
        }
    }

    public function toQueryParameters(): string
    {
        $q = \sprintf('&ll=%s,%s', $this->latitude, $this->longitude);

        if (\count($this->categories) > 0) {
            $q .= \sprintf('&categoryId=%s', \implode(',', $this->categories));
        }

        return $q;
    }
}
