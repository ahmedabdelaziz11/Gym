<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:subscription-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.subscriptions.index');
    }
}