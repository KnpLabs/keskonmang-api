<?php

namespace App\Symfony\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {        
        $request = $event->getRequest();

        if ('json' === $request->getContentType()) {
            $request->request->replace(
                null !== ($data = \json_decode($request->getContent(), true)) ?
                $data :
                []
            );
        }        
    }
}