<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index']]);
    }

    public function index()
    {
        return view('dashboard.users.index');
    }
}
