<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 255],
            KernelEvents::RESPONSE => ['onKernelResponse', 0],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_OPTIONS || !$this->isAllowedOrigin($request)) {
            return;
        }

        $response = new Response('', Response::HTTP_NO_CONTENT);
        $this->applyCorsHeaders($request, $response);

        $event->setResponse($response);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->isAllowedOrigin($request)) {
            return;
        }

        $this->applyCorsHeaders($request, $event->getResponse());
    }

    private function isAllowedOrigin(Request $request): bool
    {
        $origin = $request->headers->get('Origin');

        if ($origin === null) {
            return false;
        }

        if (in_array($origin, ['http://localhost:3000', 'http://127.0.0.1:3000'], true)) {
            return true;
        }

        return (bool) preg_match('/^http:\/\/(?:\d{1,3}\.){3}\d{1,3}:3000$/', $origin);
    }

    private function applyCorsHeaders(Request $request, Response $response): void
    {
        $origin = $request->headers->get('Origin');

        if ($origin === null) {
            return;
        }

        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Vary', 'Origin', false);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');

        $requestedHeaders = $request->headers->get('Access-Control-Request-Headers');
        $allowHeaders = $requestedHeaders ?: 'Content-Type, Authorization';

        $response->headers->set('Access-Control-Allow-Headers', $allowHeaders);
        $response->headers->set('Access-Control-Max-Age', '3600');
    }
}
