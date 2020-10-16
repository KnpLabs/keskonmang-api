<?php

namespace App\Symfony\Filter;

use App\Yelp\YelpFilter;
use Symfony\Component\HttpFoundation\Request;

class Restaurant implements YelpFilter
{
    // @see https://www.yelp.com/developers/documentation/v3/all_category_list
    const DEFAULT_CATEGORY = 'restaurants';
    const CUISINE_TYPE_CATEGORIES = [
        'bistros',
        'brasseries',
        'burgers',
        'creperies',
        'kebab',
        'noodles',
        'pizza',
        'salad',
        'sandwiches',
        'steak',
        'sushi',
        'wok',
        'wraps',
    ];
    const DIET_CATEGORIES = [
        'halal',
        'vegetarian',
        'gluten_free',
    ];
    const SUPPORTED_CATEGORIES = [
        ...self::CUISINE_TYPE_CATEGORIES,
        ...self::DIET_CATEGORIES,
        self::DEFAULT_CATEGORY,
    ];
    const SUPPORTED_PRICES_LEVEL = [1, 2, 3, 4];
    const DEFAULT_RADIUS = 2000;
    const LIMIT_RESULTS = 50;

    /** @var string */
    public $location;

    /** @var int search perimeter in meters */
    public $radius;

    /** @var array an array of price level */
    public $prices;

    /** @var array an array of category names */
    public $categories;

    public function __construct(Request $request)
    {
        $this->location = $request->query->get('location', null);

        if (!$this->location) {
            throw new \Exception('Location are a mandatory parameter.');
        }

        $this->radius = $request->query->getInt('radius', self::DEFAULT_RADIUS);
        $this->categories = [];

        foreach ($request->query->get('categories', []) as $category) {
            if (\in_array($category, self::SUPPORTED_CATEGORIES)) {
                $this->categories[] = $category;
            }
        }

        $this->prices = [];

        foreach ($request->query->get('prices', []) as $priceLevel) {
            if (\in_array($priceLevel, self::SUPPORTED_PRICES_LEVEL)) {
                $this->prices[] = $priceLevel;
            }
        }
    }

    public function toQueryParameters(): string
    {
        $q = \sprintf('&location=%s', $this->location);

        if (\count($this->categories) > 0) {
            $q .= \sprintf('&categories=%s', \implode(',', $this->categories));
        } else {
            $q .= \sprintf('&categories=%s', self::DEFAULT_CATEGORY);
        }

        if ($this->radius !== null) {
            $q .= \sprintf('&radius=%d', $this->radius);
        }

        if (\count($this->prices) > 0) {
            $q .= \sprintf('&price=%s', \implode(',', $this->prices));
        }

        $q .= \sprintf("&limit=", self::LIMIT_RESULTS);

        return $q;
    }
}
