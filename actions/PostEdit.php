<?php

namespace App\Actions;

use App\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Exception\ValidationException;
use WebFramework\Presentation\MessageService;
use WebFramework\Presentation\RenderService;
use WebFramework\Security\AuthenticationService;
use WebFramework\Validation\InputValidationService;
use WebFramework\Validation\Validator\CustomValidator;

class PostEdit
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private InputValidationService $inputValidationService,
        private MessageService $messageService,
        private PostRepository $postRepository,
        private RenderService $renderer,
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
            $this->messageService->add('error', 'You can only edit your own posts');

            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        $params = [
            'user' => $user,
            'post' => $post,
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
        ];

        if (!$request->getAttribute('passed_csrf'))
        {
            return $this->renderer->render($request, $response, 'PostForm.latte', $params);
        }

        try
        {
            $filtered = $this->inputValidationService->validate(
                [
                    'title' => (new CustomValidator('title'))
                        ->required()
                        ->minLength(3)
                        ->maxLength(255),
                    'content' => (new CustomValidator('content'))
                        ->required()
                        ->minLength(10),
                ],
                $request->getParams(),
            );

            $post->setTitle($filtered['title']);
            $post->setContent($filtered['content']);
            $post->setUpdatedAt(time());

            $this->postRepository->save($post);

            $this->messageService->add('success', 'Post updated successfully');

            return $response->withHeader('Location', '/posts')->withStatus(302);
        }
        catch (ValidationException $e)
        {
            $this->messageService->addErrors($e->getErrors());
            $all = $this->inputValidationService->getAll();

            $params = array_merge($params, $all);
        }

        return $this->renderer->render($request, $response, 'PostForm.latte', $params);
    }
}
