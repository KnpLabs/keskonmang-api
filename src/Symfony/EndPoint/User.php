<?php

namespace App\Symfony\EndPoint;

use App\Symfony\JsonDefinition\User as UserDefinition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class User extends Controller
{
    public function me(): JsonResponse
    {
        return new JsonResponse(new UserDefinition($this->getUser()));
    }
}
