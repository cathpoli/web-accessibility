<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AuditsController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditsController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Audits/Index', [
        ]);
    }
}