<?php

namespace App\Symfony\EndPoint;

use App\Symfony\JsonDefinition\User as UserDefinition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class User extends AbstractController
{
    public function me(): JsonResponse
    {
        $user = $this->getUser();    
        $def = new UserDefinition($user);

        return new JsonResponse($def);
    }
}
