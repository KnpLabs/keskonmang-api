<?php

namespace App\Symfony\EndPoint;

use App\Symfony\Filter\Restaurant as RestaurantFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Filter extends AbstractController
{
    public function getCuisineTypes(Request $request): JsonResponse
    {
        return new JsonResponse(RestaurantFilter::CUISINE_TYPE_CATEGORIES);
    }

    public function getDiets(Request $request): JsonResponse
    {
        return new JsonResponse(RestaurantFilter::DIET_CATEGORIES);
    }

    public function getPrices(Request $request): JsonResponse
    {
        return new JsonResponse(RestaurantFilter::SUPPORTED_PRICES_LEVEL);
    }
}
