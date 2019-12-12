<?php

namespace App\Foursquare;

interface FoursquareFilter
{
    public function toQueryParameters(): string;
}
