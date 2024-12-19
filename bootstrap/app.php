<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, $request) {
            if ($request->wantsJson()) {
                return match (true) {
                    $e instanceof BackedEnumCaseNotFoundException => failed(__('serverError.page_not_found'), 404),
                    $e instanceof ModelNotFoundException => failed(__('serverError.model_not_found'), 403),
                    $e instanceof AuthenticationException => failed(__('serverError.authentication_exception'), 401),
                    $e instanceof SuspiciousOperationException => failed(__('serverError.suspicious_operation_exception'), 403),
                    $e instanceof RecordsNotFoundException => failed(__('serverError.records_not_found_exception'), 404),
                    $e instanceof RouteNotFoundException => failed(__('serverError.route_not_found_exception'), 404),
                    $e instanceof NotFoundHttpException => failed(__('serverError.not_found_http_exception'), 404),
                    $e instanceof ValidationException => response()->json([
                        'message' => $e->getMessage(),
                        'errors' => $e->errors(),
                    ], $e->status),
                    default => failed($e->getMessage(), $e->getCode()),
                };
            }
        });
    })->create();
