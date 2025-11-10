<?php

namespace App\Routes;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use WebFramework\Http\RouteSet;
use WebFramework\Middleware\LoggedInMiddleware;

class Authenticated implements RouteSet
{
    public function __construct(
        private LoggedInMiddleware $loggedInMiddleware,
    ) {
    }

    public function register(App $app): void
    {
        $app->group('', function (RouteCollectorProxy $group) {
            // Profile page
            $group->get('/profile', \App\Actions\Profile::class);

            // Change password
            $group->get('/change-password', \WebFramework\Actions\ChangePassword::class);
            $group->post('/change-password', \WebFramework\Actions\ChangePassword::class);

            // Change email
            $group->get('/change-email', \WebFramework\Actions\ChangeEmail::class);
            $group->post('/change-email', \WebFramework\Actions\ChangeEmail::class);
            $group->get('/change-email/verify', \WebFramework\Actions\ChangeEmailVerify::class);

            // Posts (Database/ORM demo)
            $group->get('/posts', \App\Actions\PostsList::class);
            $group->get('/posts/create', \App\Actions\PostCreate::class);
            $group->post('/posts/create', \App\Actions\PostCreate::class);
            $group->get('/posts/{id}/edit', \App\Actions\PostEdit::class);
            $group->post('/posts/{id}/edit', \App\Actions\PostEdit::class);
            $group->get('/posts/{id}/delete', \App\Actions\PostDelete::class);
        })
            ->add($this->loggedInMiddleware)
        ;
    }
}

