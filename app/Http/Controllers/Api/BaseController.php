<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function responseApi($data, $status, $message, $code)
    {
        return response()->json([
            'data' => $data,
            'message' => __($message),
            'status' => $status
        ], $code);
    }
}
