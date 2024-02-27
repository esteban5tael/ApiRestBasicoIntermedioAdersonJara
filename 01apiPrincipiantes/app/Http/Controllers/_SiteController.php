<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class _SiteController extends Controller
{
        public function apiIndex(){
            $data = [
                'success' => true,
                'message' => 'Welcome to My API'
            ];
        
            return response()->json([
                $data
            ], 200);
        }
}
