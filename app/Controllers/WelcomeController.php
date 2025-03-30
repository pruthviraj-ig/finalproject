<?php

namespace App\Controllers;

class WelcomeController extends BaseController
{
    public function index()
    {
        return view('welcome');
    }
}
