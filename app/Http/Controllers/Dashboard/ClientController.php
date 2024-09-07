<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:client-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.clients.index');
    }
}