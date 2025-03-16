<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
