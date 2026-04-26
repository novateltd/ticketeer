<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function __invoke()
    {
        $events = Event::query()->active()->get();

        return view('home', compact('events'));
    }
}
