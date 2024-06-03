<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeapotController extends Controller
{
    public function show()
    {
        return response("I'm a teapot", 418);
    }
}
