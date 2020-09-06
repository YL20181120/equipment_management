<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($data = [])
    {
        return response()->json($data);
    }

    public function message($message, $statusCode = 200, $headers = [])
    {
        return response()->json([
            "message" => $message,
        ])->setStatusCode($statusCode)->withHeaders($headers);
    }

    public function failed($message, $statusCode = 403, $code = 0, $headers = [])
    {
        throw new HttpException($statusCode, $message, null, $headers, $code);
    }
}
