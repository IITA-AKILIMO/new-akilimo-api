<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    abstract public function index(Request $request);
}
