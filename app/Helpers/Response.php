<?php

namespace App\Helpers;

// this class is used to generate the response based on the status code
// and data provided from the controller
class Response
{   // this function has some harcoded status code and corresponding 
    // error message and is used to generate the reponse
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
