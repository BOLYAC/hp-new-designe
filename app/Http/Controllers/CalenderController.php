<?php

namespace App\Http\Controllers;

use App\Models\Event;

class CalenderController extends Controller
{
    public function __invoke()
    {
        $events = Event::all();
       return view('calender.index', compact('events'));
    }
}
