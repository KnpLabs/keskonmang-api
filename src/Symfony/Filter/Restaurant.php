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
    const DEFAULT_RADIUS = 2000; // in meters
    const LIMIT_RESULTS = 50;

    public string $location;
    public int $radius;
    public array $prices = [];
    public array $categories = [];

    public function __construct(Request $request)
    {
        if (!$location = $request->query->get('location', null)) {
            throw new \Exception('Location are a mandatory parameter.');
        }

        $this->location = $location;
        $this->radius   = $request->query->getInt('radius', self::DEFAULT_RADIUS);

        foreach ($request->query->get('categories', []) as $category) {
            if (\in_array($category, self::SUPPORTED_CATEGORIES)) {
                $this->categories[] = $category;
            }
        }

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

        $q .= \sprintf('&limit=%d', self::LIMIT_RESULTS);
        $q .= '&open_now=1';

        return $q;
    }
}
