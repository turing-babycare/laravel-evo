<?php


namespace Turing\LaravelEvo\Microservice;


class Reply
{
    protected $trace_id;

    public function __construct()
    {
        if (!empty($_SERVER['HTTP_X_REQUEST_ID'])) {
            $this->trace_id = $_SERVER['HTTP_X_REQUEST_ID'];
        } else if (!empty($_SERVER['X-REQUEST-ID'])) {
            $this->trace_id = $_SERVER['X-REQUEST-ID'];
        } else {
            $this->trace_id = 'local-dev-mode';
        }
    }

    public function getTraceId()
    {
        return $this->trace_id;
    }

    public function resp($payload, $code = 2000, $message = '')
    {
        return response()->json([
            'trace_id' => $this->trace_id,
            'code' => $code,
            'data' => $payload,
            'message' => $message
        ]);
    }

    public function fail($message, $httpCode = 400, $code = 5000, $detail = null)
    {
        return response()->json([
            'trace_id' => $this->trace_id,
            'code' => $code,
            'message' => $message,
            'detail' => $detail
        ], $httpCode);
    }
}