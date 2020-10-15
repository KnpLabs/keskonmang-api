<?php

namespace App\Yelp;

interface YelpFilter
{
    public function toQueryParameters(): string;
}
