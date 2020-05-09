<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;

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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            return response()->view('errors.' . 'error', ['is_admin' => false, 'erro_code' => $exception->getStatusCode(), 'property_not_found' => $exception->getMessage(), 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization'], $exception->getStatusCode());
        }else{
            return response()->view('errors.' . 'error', ['is_admin' => false, 'erro_code' => 404, 'property_not_found' => null, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization'], 404);
        }
        
        return parent::render($request, $exception);
    }
}
