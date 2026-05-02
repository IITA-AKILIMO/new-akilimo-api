<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class PlaygroundController extends Controller
{

    public function show(): View
    {
        return view('playground');
    }
}
