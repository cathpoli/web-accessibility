<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SampleController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Sample/Show');
    }
}
