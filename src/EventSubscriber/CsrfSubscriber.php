<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CsrfSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        if ($request->isMethodSafe()) {
            return;
        }

        $token = $request->headers->get('csrf-token');
        $cookie = $request->cookies->get('csrf-token_csrf-token');

        if (!$token || !$cookie) {
            $event->setResponse(new JsonResponse([
                'message' => 'Token CSRF manquant',
            ], 403));

            return;
        }

        if (!hash_equals($cookie, 'csrf-token')) {
            $event->setResponse(new JsonResponse([
                'message' => 'Token CSRF invalide',
            ], 403));

            return;
        }

        if ($token !== $cookie) {
            $event->setResponse(new JsonResponse([
                'message' => 'Token CSRF invalide',
            ], 403));
        }
    }
}
