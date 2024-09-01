<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lead-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.leads.index');
    }
}