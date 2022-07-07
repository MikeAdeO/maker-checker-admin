<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('admin_is_checker')) {
    function admin_is_checker()
    {
        if (request()->user()->role === 'checker') {
            return true;
        }
        return response()->json([
            "message" => "you dont have access"
        ], 401);
    }
}
