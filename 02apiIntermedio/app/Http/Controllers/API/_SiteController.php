<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class _SiteController extends Controller
{
    public function index()
    {
        $data = 'Welcome To My Api';

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'API Retrieved Successfully.'
        ], 200);
    }
}
