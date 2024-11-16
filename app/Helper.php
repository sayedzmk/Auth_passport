<?php
if (!function_exists('AuthApi')) {
    function AuthApi()
    {
        return auth()->guard('api');
    }
}

if (!function_exists('res_data')) {
    function res_data($data,$token, $message = null, $status = 200)
    {
        $message = $message ;
        return response([
            'message' => $message,
            'result' => !empty($data) ? $data : null,
            'token' => $token ?: [],
            'statusCode' => $status,
            'status' => in_array($status, [200, 201, 203])

        ], $status);
    }
}
