<?php

namespace App\Actions;

use App\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Presentation\MessageService;
use WebFramework\Security\AuthenticationService;

class PostDelete
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private MessageService $messageService,
        private PostRepository $postRepository,
    ) {}

    /**
     * @param array<string, string> $routeArgs
     */
    public function __invoke(Request $request, Response $response, array $routeArgs): ResponseInterface
    {
        $user = $this->authenticationService->getAuthenticatedUser();

        $postId = (int) $routeArgs['id'];
        $post = $this->postRepository->getObjectById($postId);

        if ($post === null)
        {
            $this->messageService->add('error', 'Post not found');

            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        if ($post->getUserId() !== $user->getId())
        {
            $this->messageService->add('error', 'You can only delete your own posts');

            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        $this->postRepository->delete($post);
        $this->messageService->add('success', 'Post deleted successfully');

        return $response->withHeader('Location', '/posts')->withStatus(302);
    }
}
