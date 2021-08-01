<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class NewPasswordController extends Controller
{
    public function create()
    {
        return view('auth.reset-password');
    }
}
