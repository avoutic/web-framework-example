<?php

namespace App\Presentation;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use WebFramework\Core\RuntimeEnvironment;
use WebFramework\Presentation\LatteRenderService;
use WebFramework\Presentation\MessageService;
use WebFramework\Presentation\RenderService;
use WebFramework\Security\CsrfService;

/**
 * Custom render service that automatically includes messages in template parameters.
 *
 * This service wraps a RenderService implementation and automatically adds
 * messages from MessageService to all template parameters.
 */
class AppRenderService implements RenderService
{
    public function __construct(
        private CsrfService $csrfService,
        private LatteRenderService $renderService,
        private MessageService $messageService,
        private RuntimeEnvironment $runtimeEnvironment,
    ) {}

    /**
     * Render a template and write the output to the response body.
     *
     * Automatically includes messages from MessageService in template parameters.
     *
     * @param Request              $request      The current request object
     * @param Response             $response     The response object to write to
     * @param string               $templateFile The name of the template file to render
     * @param array<string, mixed> $params       An associative array of parameters to pass to the template
     *
     * @return Response The modified response object with the rendered content
     */
    public function render(Request $request, Response $response, string $templateFile, array $params = []): Response
    {
        // Automatically include messages in template parameters

        $params['baseUrl'] = $this->runtimeEnvironment->getBaseUrl();
        $params['csrfToken'] = $this->csrfService->getToken();
        $params['messages'] = $this->messageService->getMessages();

        return $this->renderService->render($request, $response, $templateFile, $params);
    }

    /**
     * Render a template and return the output as a string.
     *
     * Automatically includes messages from MessageService in template parameters.
     *
     * @param Request              $request      The current request object
     * @param string               $templateFile The name of the template file to render
     * @param array<string, mixed> $params       An associative array of parameters to pass to the template
     *
     * @return string The rendered content as a string
     */
    public function renderToString(Request $request, string $templateFile, array $params = []): string
    {
        // Automatically include messages in template parameters
        $params['messages'] = $this->messageService->getMessages();

        return $this->renderService->renderToString($request, $templateFile, $params);
    }
}
