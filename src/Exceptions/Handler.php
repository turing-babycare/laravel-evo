<?php


namespace Turing\LaravelEvo\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;
use Turing\LaravelEvo\Facades\Reply;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof TooManyRequestsHttpException) {
            return Reply::fail('操作过于频繁，请稍后重试！', 400, 5000);
        }

        if ($e instanceof NotFoundHttpException) {
            return Reply::fail('接口不存在！', 404, 5000);
        }

        return Reply::fail(
            '服务器错误: ' . $e->getMessage(),
            500,
            5000,
            config('app.mode') === 'production' ? null : "Exception: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}\n[stacktrace]\n{$e->getTraceAsString()}"
        );
    }
}