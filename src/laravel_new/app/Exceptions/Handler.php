<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException)
            $exception = new ModelNotFoundException('Não encontramos este registro!', $exception->getCode(), $exception);
        $json = parent::render($request, $exception);
        if (!method_exists($json, 'getData')) return $json;
        $json->setStatusCode(200);
        $data = $json->getData();
        $data->result = false;
        if (!empty($data->errors)) {
            $data->msg = [];
            foreach ($data->errors ?? [[$data->message]] as $erros) {
                $data->msg[] = implode(', ', $erros);
            }
            $data->msg = implode(', ', $data->msg);
        } elseif (!empty($data->message)) {
            $data->msg = $data->message;
        }
        $json->setData($data);
        return $json;
    }
}
