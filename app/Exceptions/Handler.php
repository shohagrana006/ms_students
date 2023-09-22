<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthorized',
                'code'  => 401 
            ], Response::HTTP_UNAUTHORIZED); 
        }

        $this->renderable(function (NotFoundHttpException $e,Request $request) {
            if (request()->ajax() || request()->wantsJson() || $request->is('api/*') ) {
                return response()->json([
                    'errors' => ['Object not found'],
                ], 404);
            }
        });
        return parent::render($request, $exception);

        
    }

    



}
