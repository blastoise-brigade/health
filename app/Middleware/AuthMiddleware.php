<?php

namespace App\Middleware;

class AuthMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        if (!isset($_SESSION['ps_id'])) {
            $this->container->flash->addMessage('error', 'Please sign in before doing that.');
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
