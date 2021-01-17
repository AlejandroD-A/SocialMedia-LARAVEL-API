<?php

namespace App\Traits;

trait ApiResponse
{

    public function successResponse($data = [], $code = "200", $msj = '')
    {
        return response()->json(["data" => $data, "code" => $code, "msj" => $msj], $code);
    }

    public function errorResponse($data = [], $code = "500", $msj = '')
    {
        return response()->json(["data" => $data, "code" => $code, "msj" => $msj], $code);
    }
}
