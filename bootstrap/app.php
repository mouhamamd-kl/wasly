<?php

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use App\Http\Middleware\CustomRedirectIfAuthenticated;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web/web.php',
        api: __DIR__ . '/../routes/api/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {}
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);



        //
        $middleware->web(remove: [
            RedirectIfAuthenticated::class,
        ]);
        $middleware->alias([
            'guest' => \App\Http\Middleware\CustomRedirectIfAuthenticated::class,
            'admin' => \App\Http\Middleware\AdminMiddleWare::class
        ]);
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /**
         * Render an exception into an HTTP response.
         * 
         * @param \Illuminate\Http\Request $request
         * @param \Throwable $e
         * @return \Symfony\Component\HttpFoundation\Response
         */




        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return ApiResponse::sendResponse(code: 401, msg: 'Not Authenticated', data: []);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            // Traverse the exception chain to find MissingAbilityException
            $previousException = $e->getPrevious();
            while ($previousException) {
                if ($previousException instanceof MissingAbilityException) {
                    return ApiResponse::sendResponse(code: 403, msg: 'You do not have the required authorization to perform this action.', data: []);
                }
                $previousException = $previousException->getPrevious();
            }
            // If MissingAbilityException is not found, proceed with default handling
            return null;
        });
        
    })->create();
