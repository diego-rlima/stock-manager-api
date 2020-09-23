<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        //
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => __($exception->getMessage())], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => __($exception->getMessage()),
            'errors' => $exception->errors(),
        ], $exception->status);
    }


    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $exception
     * @return array
     */
    protected function convertExceptionToArray(Throwable $exception)
    {
        $data = parent::convertExceptionToArray($exception);

        if ($exception instanceof PostTooLargeException) {
            $data['message'] = 'The file is too large.';
        } elseif ($exception instanceof NotFoundHttpException) {
            $data['message'] = 'Resource not found.';
        } elseif (
            $exception instanceof MaintenanceModeException
            && empty($data['message'])
        ) {
            $data['message'] = 'We need a little more time to make the last adjustments. Please try again later.';
        }

        $debug = config('app.debug');
        $message = !empty($data['message']) ? __($data['message']) : null;

        if (!$message || (!$debug && $message == __($data['message']))) {
            $message = __('Oops! Something went wrong! Please try again soon.');
        }

        $data['message'] = $message;

        return $data;
    }
}
