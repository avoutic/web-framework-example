<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use WebFramework\Presentation\RenderService;

class TranslationsDemo
{
    public function __construct(
        private RenderService $renderer,
    ) {}

    /**
     * @param array<string, string> $routeArgs
     */
    public function __invoke(Request $request, Response $response, array $routeArgs): ResponseInterface
    {
        // Example of using translations in PHP code
        $translatedTitle = __('demo', 'title');
        $translatedWelcome = __('demo', 'welcome');

        // Example with parameter replacement (if we had one)
        // $message = __('demo', 'greeting', ['name' => 'John']);

        // Get all translations for a category
        $demoTranslations = __C('demo');

        $params = [
            'translatedTitle' => $translatedTitle,
            'translatedWelcome' => $translatedWelcome,
            'demoTranslations' => $demoTranslations,
        ];

        return $this->renderer->render($request, $response, 'TranslationsDemo.latte', $params);
    }
}
