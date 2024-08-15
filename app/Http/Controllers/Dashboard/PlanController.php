<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:plan-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.plans.index');
    }
}