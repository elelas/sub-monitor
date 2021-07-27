<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class PhoneController extends Controller
{
    public function index()
    {
        return view('auth.phone');
    }
}