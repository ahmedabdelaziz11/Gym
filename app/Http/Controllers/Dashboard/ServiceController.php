<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:service-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.services.index');
    }
}