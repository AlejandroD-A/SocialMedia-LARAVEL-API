<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
        $this->reportable(function (Throwable $e) {
        });
        $this->renderable(function (Exception $e, $request) {


            if (env('APP_ENV') == 'other') {
                if ($e instanceof NotFoundHttpException) {
                    return $this->errorResponse("Pagina no encontrada", $code = 404, $msj = "Pagina no encontrada");
                }

                if ($e instanceof ModelNotFoundException) {
                    return $this->errorResponse("Recurso no encontrado", $code = 404, $msj = "Recurso no encontrado");
                }
            }
        });
    }
}
