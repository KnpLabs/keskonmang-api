<?php

namespace App\Symfony\EndPoint;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Symfony\Form\UserType;

class User extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $form = $this->createForm(UserType::class);
        $form->submit($request->request->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            // TODO handle errors
        }

        // fetch user from google using the token from the request
        return new JsonResponse([
            'message' => 'test endpoint'
        ]);
    }
}
