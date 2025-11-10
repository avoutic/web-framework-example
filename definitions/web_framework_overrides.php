<?php

/**
 * In this definitions file you put the definitions that override or specify
 * classes or interfaces from the WebFramework itself.
 *
 * Most often the Interfaces that default to a Null implementation such as:
 *  - Cache\Cache
 *  - Database\Database
 *  - Diagnostics\ReportFunction
 *  - Mail\MailService
 *  - Security\AuthenticationService
 *  - Security\BlacklistService
 */

namespace WebFramework;

use App\Presentation\AppRenderService;
use App\Security\RegisterExtension;
use DI;

return [
    'app_name' => 'demo',

    Cache\Cache::class => DI\get(Redis\RedisCache::class),
    Database\Database::class => DI\get(Mysql\MysqliDatabase::class),
    Mail\MailService::class => DI\get(Postmark\PostmarkMailService::class),
    Mail\MailBackend::class => DI\get(Postmark\PostmarkMailService::class),
    Presentation\RenderService::class => DI\autowire(AppRenderService::class),
    Security\AuthenticationService::class => DI\get(Security\DatabaseAuthenticationService::class),
    Security\Extension\RegisterExtensionInterface::class => DI\autowire(RegisterExtension::class),
];
