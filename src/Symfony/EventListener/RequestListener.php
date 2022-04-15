<?php

namespace App\Symfony\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {        
        $request = $event->getRequest();

        if ('json' === $request->getContentType()) {
            $data = \json_decode($request->getContent(), true);
            $request->request->replace($data ?? []);
        }        
    }
}
