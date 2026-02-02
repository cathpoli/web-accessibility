<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocumentationController extends Controller
{
    public function index(Request $request) : Response
    {
        return Inertia::render('Client/Documentation/Index');
    }
}
