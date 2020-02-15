<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

final class HomeController extends Controller
{
    /**
     * Home page view.
     *
     * @return View
     */
    public function index(): View
    {
        return view('home');
    }
}
