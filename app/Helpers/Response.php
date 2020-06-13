<?php

namespace App\Helpers;

class Response
{
    public function response($code, $data = null)
    {
        $responses = [
            200 => "Success",
            400 => "Bad Request",
            401 => "Unauthorized",
            403 => "Forbidden",
            404 => "Not Found",
            500 => "Internal Server Error",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout",
        ];

        if (!is_null($data)) {
            return
                [
                    "message" => $responses[$code],
                    "code" => $code,
                    "info" => $data,
                ];
        }

        return
            [
                "message" => $responses[$code],
                "code" => $code,
            ];
    }
}
