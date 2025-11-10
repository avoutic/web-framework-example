<?php

use App\Routes\Authenticated;
use App\Routes\Unauthenticated;
use Odan\Session\Middleware\SessionStartMiddleware;
use WebFramework\Middleware\AuthenticationMiddleware;
use WebFramework\Middleware\CsrfValidationMiddleware;
use WebFramework\Middleware\ErrorRedirectMiddleware;
use WebFramework\Middleware\IpMiddleware;
use WebFramework\Middleware\JsonParserMiddleware;
use WebFramework\Middleware\MessageMiddleware;
use WebFramework\Middleware\SecurityHeadersMiddleware;
use WebFramework\SanityCheck\RequiredAuth;

/*
 * This configuration file changes the base_config.php from WebFramework
 * itself.
 *
 * It's advised to put this in production-ready mode and use a
 * 'config_local.php' layered on top to modify it to debug / non-production.
 */

return [
    'production' => true,
    'authenticator' => [
        'unique_identifier' => 'name',
        'session_timeout' => 86400,
    ],
    'security' => [
        'hmac_key' => 'CHANGETHISTO20CHARRANDOMSTRING',
        'crypt_key' => 'CHANGETHISTO20CHARRANDOMSTRING',
    ],
    'debug' => true,
    'sanity_check_modules' => [
        RequiredAuth::class => [
            'db_config.main.php',
            'redis.php',
            'postmark.php',
        ],
    ],
    'sender_core' => [
        'default_sender' => env('POSTMARK_SENDER_EMAIL'),
        'assert_recipient' => env('ASSERT_RECIPIENT_EMAIL'),
    ],
    'definition_files' => [
        '../vendor/avoutic/web-framework/definitions/definitions.php',
        '../vendor/avoutic/web-framework-mysql/definitions/definitions.php',
        '../vendor/avoutic/web-framework-redis/definitions/definitions.php',
        '../vendor/avoutic/web-framework-postmark/definitions/definitions.php',
        'web_framework_overrides.php',
        'app_definitions.php',
        '?local_definitions.php',
    ],
    'middlewares' => [
        'pre_routing' => [
            // End of stack
            ErrorRedirectMiddleware::class,
            // Start of stack
        ],
        'post_routing' => [
            // End of stack
            SecurityHeadersMiddleware::class,
            MessageMiddleware::class,
            JsonParserMiddleware::class,
            CsrfValidationMiddleware::class,
            AuthenticationMiddleware::class,
            IpMiddleware::class,
            SessionStartMiddleware::class,
            ErrorRedirectMiddleware::class,
            // Start of stack
        ],
    ],
    'routes' => [
        Unauthenticated::class,
        Authenticated::class,
    ],
];
