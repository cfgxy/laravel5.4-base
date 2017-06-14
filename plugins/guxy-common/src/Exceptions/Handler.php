<?php

namespace Guxy\Common\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/**
 * 异常的处理类
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     * 报告异常
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof AppException) {
            guxy_json_message($exception->getMessage(), $exception->getCode())->send();
            exit;
        }

        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function prepareResponse($request, Exception $e)
    {
        if (config('app.debug') && !$this->isHttpException($e)) {
            return $this->renderExceptionWithWhoops($request, $e);
        }

        return parent::prepareResponse($request, $e);
    }

    protected function renderExceptionWithWhoops($request, Exception $e)
    {
        $whoops = app(\Whoops\Run::class);
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }
}
