<?php

namespace App\Symfony\JsonDefinition;

class Restaurant
{
    public ?string $id;
    public ?string $name;
    public ?string $url;
    public ?string $image;
    public ?string $phone;
    public ?int $price;
    public ?string $address;
    public ?string $zipCode;
    public ?string $city;
    public ?string $country;

    public function __construct(object $restaurantData)
    {
        $this->id = $restaurantData->id;
        $this->name = $restaurantData->name;
        $this->url = $restaurantData->url;
        $this->image = $restaurantData->image_url;
        $this->phone = $restaurantData->display_phone;
        $this->price = property_exists($restaurantData, 'price') ? mb_strlen($restaurantData->price) : null;
        $this->zipCode = $restaurantData->location->zip_code;
        $this->city = $restaurantData->location->city;
        $this->country = $restaurantData->location->country;
        $this->address = $restaurantData->location->address1;

        if ($restaurantData->location->address2 && $restaurantData->location->address3) {
            $this->address .= sprintf(
                ' (%s - %s)',
                $restaurantData->location->address2,
                $restaurantData->location->address3
            );
        } else if ($restaurantData->location->address2) {
            $this->address .= sprintf(' (%s)', $restaurantData->location->address2);
        }
    }
}
