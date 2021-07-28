<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function email()
    {
        return view('auth.email');
    }

    public function phone()
    {
        return view('auth.phone');
    }
}
