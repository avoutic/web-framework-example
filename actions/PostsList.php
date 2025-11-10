<?php

namespace App\Actions;

use App\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Presentation\RenderService;
use WebFramework\Security\AuthenticationService;

class PostsList
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private PostRepository $postRepository,
        private RenderService $renderer,
    ) {}

    /**
     * @param array<string, string> $routeArgs
     */
    public function __invoke(Request $request, Response $response, array $routeArgs): ResponseInterface
    {
        $user = $this->authenticationService->getAuthenticatedUser();
        $posts = $this->postRepository->getAllPosts(0, 50);

        $params = [
            'user' => $user,
            'posts' => $posts,
        ];

        return $this->renderer->render($request, $response, 'PostsList.latte', $params);
    }
}
