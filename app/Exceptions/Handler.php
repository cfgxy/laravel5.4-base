<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Guxy\Common\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];


    public function unauthenticated(Request $request, \Exception $ex)
    {
        if (!$request->routeIs('login')) {
            if ($request->isXmlHttpRequest()) {
                return guxy_json_message(trans('auth.unauth'), 401);
            } else {
                return redirect(route('login', [], false));
            }
        }


        abort(404);
    }
}
