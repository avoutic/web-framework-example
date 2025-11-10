<?php

namespace App\Actions;

use App\Entity\Post;
use App\Event\PostCreated;
use App\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Event\EventService;
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
        private EventService $eventService,
        private LogService $logService,
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

            // Log post creation
            $this->logService->info('app', 'Post created (Direct log)', [
                'user_id' => $user->getId(),
                'post_id' => $post->getId(),
                'post_title' => $post->getTitle(),
            ]);

            // Dispatch PostCreated event
            $this->eventService->dispatch(new PostCreated($request, $post));

            $this->messageService->add('success', 'Post created successfully');

            return $response->withHeader('Location', '/posts')->withStatus(302);
        }
        catch (ValidationException $e)
        {
            $this->logService->warning('app', 'Post creation validation failed', [
                'user_id' => $user->getId(),
                'errors' => $e->getErrors(),
            ]);

            $this->messageService->addErrors($e->getErrors());
            $all = $this->inputValidationService->getAll();

            $params = array_merge($params, $all);
        }

        return $this->renderer->render($request, $response, 'PostForm.latte', $params);
    }
}
