<?php

namespace App\Middleware;

class GuestMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['ps_id'])) {
            $this->container->flash->addMessage('warning', 'You are already logged in.');
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
