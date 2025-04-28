<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function employee()
    {
        return view('employee.dashboard');
    }

    public function owner()
    {
        return view('owner.dashboard');
    }
}
