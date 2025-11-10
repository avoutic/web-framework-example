<?php

namespace App\Routes;

use Slim\App;
use WebFramework\Http\RouteSet;

class Unauthenticated implements RouteSet
{
    public function register(App $app): void
    {
        // Homepage
        $app->get('/', \App\Actions\Main::class);

        // Registration
        $app->get('/register', \WebFramework\Actions\Register::class);
        $app->post('/register', \WebFramework\Actions\Register::class);
        $app->get('/register-verify', \WebFramework\Actions\RegisterVerify::class);

        // Login / verification
        $app->get('/login', \WebFramework\Actions\Login::class);
        $app->post('/login', \WebFramework\Actions\Login::class);
        $app->get('/login-verify', \WebFramework\Actions\LoginVerify::class);

        // Logout
        $app->get('/logoff', \WebFramework\Actions\Logoff::class);

        // Password reset
        $app->get('/reset-password', \WebFramework\Actions\ResetPassword::class);
        $app->post('/reset-password', \WebFramework\Actions\ResetPassword::class);
        $app->get('/reset-password-verify', \WebFramework\Actions\ResetPasswordVerify::class);

        // Verification
        $app->get('/verify', \WebFramework\Actions\Verify::class);
        $app->post('/verify', \WebFramework\Actions\Verify::class);
        $app->post('/verify/resend', \WebFramework\Actions\VerifyResend::class);

        // Translations demo
        $app->get('/translations-demo', \App\Actions\TranslationsDemo::class);
    }
}
