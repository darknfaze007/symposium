<?php

namespace App\Http\Controllers;

use App\Conference;
use Calendar;

class CalendarController extends BaseController
{
    public function index()
    {
        $conferences = Conference::all()->map(function ($conference) {
            return Calendar::event(
                $conference->title,
                true,
                $conference->starts_at,
                $conference->ends_at,
                $conference->id,
                [
                    'color' => '#428bca',
                    'url' => route('conferences.show', $conference->id),
                ]
            );
        })->toArray();

        $cfps = Conference::all()->map(function ($conference) {
            return Calendar::event(
                "CFPs open for {$conference->title}",
                true,
                $conference->cfp_starts_at,
                $conference->cfp_starts_at,
                'cfp-' . $conference->id,
                [
                    'color' => '#F39C12',
                    'url' => route('conferences.show', $conference->id),
                ]
            );
        })->toArray();

        $calendar = Calendar::addEvents(array_merge($conferences, $cfps));

        return view('calendar', [
            'calendar' => $calendar,
            'options' => $calendar->getOptionsJson(),
        ]);
    }
}