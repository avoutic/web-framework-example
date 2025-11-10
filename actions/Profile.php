<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Presentation\RenderService;
use WebFramework\Security\AuthenticationService;

class Profile
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
        $user = $this->authenticationService->getAuthenticatedUser();

        $params = [
            'user' => $user,
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'verified' => $user->isVerified(),
            'registered_at' => $user->getRegistered(),
        ];

        return $this->renderer->render($request, $response, 'Profile.latte', $params);
    }
}
