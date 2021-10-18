<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {

        if($request->is('api*')) {

            if ($e instanceof \Illuminate\Database\QueryException) {
                return response([
                    'status' => 'error',
                    'errors' => $e->getMessage()
                ], 400);
            }
            if ($e instanceof \Illuminate\Contracts\Container\BindingResolutionException) {
                return response([
                    'status' => 'error',
                    'errors' => $e->getMessage()
                ], 400);
            }
            if ($e instanceof  \Illuminate\Validation\ValidationException) {
                return response([
                    'status' => 'error',
                    'errors' => $e->getMessage()
                ], 400);
            }
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return response([
                    'status' => 'error',
                    'errors' => $e->getMessage()
                ], 400);
            }
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response([
                    'status' => 'error',
                    'errors' => $e->getMessage()
                ], 400);
            }
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response([
                    'status' => 'error',
                    'errors' => $e->getMessage()
                ], 400);
            }
            if ($e instanceof \Symfony\Component\Routing\Exception\RouteNotFoundException) {
                return response([
                    'status' => 'error',
                    'errors' => "Sorry you are not Authorize to access this api route"
                ], 401);
            }
            dd($e);
        }
    }
}
