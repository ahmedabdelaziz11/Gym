<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class CallController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:call-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.calls.index');
    }
}