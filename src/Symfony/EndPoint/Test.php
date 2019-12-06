<?php

namespace App\Symfony\EndPoint;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class Test extends Controller
{
    public function test(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'test endpoint'
        ]);
    }
}
