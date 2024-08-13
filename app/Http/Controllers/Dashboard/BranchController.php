<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:branch-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.branches.index');
    }
}