<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Presentation\RenderService;
use WebFramework\Security\AuthenticationService;

class Main
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private RenderService $renderer,
    ) {}

    /**
     * @param array<string, string> $routeArgs
     */
    public function __invoke(Request $request, Response $response, array $routeArgs): ResponseInterface
    {
        $user = null;

        if ($this->authenticationService->isAuthenticated())
        {
            $user = $this->authenticationService->getAuthenticatedUser();
        }

        $params = [
            'user' => $user,
        ];

        return $this->renderer->render($request, $response, 'Main.latte', $params);
    }
}
