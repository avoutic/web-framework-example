<?php

namespace App\Actions;

use App\Entity\Post;
use App\Event\PostCreated;
use App\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Exception\ValidationException;
use WebFramework\Logging\LogService;
use WebFramework\Presentation\MessageService;
use WebFramework\Presentation\RenderService;
use WebFramework\Security\AuthenticationService;
use WebFramework\Validation\InputValidationService;
use WebFramework\Validation\Validator\CustomValidator;

class PostCreate
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

        $params = [
            'user' => $user,
            'title' => '',
            'content' => '',
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

            $post = new Post();
            $post->setTitle($filtered['title']);
            $post->setContent($filtered['content']);
            $post->setUserId($user->getId());
            $post->setCreatedAt(time());
            $post->setUpdatedAt(time());

            $this->postRepository->save($post);

            $this->messageService->add('success', 'Post created successfully');

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
