<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarEventController extends Controller
{
    public function index(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        $monthObj = Carbon::parse($month . '-01');
        $start    = $monthObj->copy()->startOfMonth();
        $end      = $monthObj->copy()->endOfMonth();

        $events = CalendarEvent::whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->with('creator')
            ->orderBy('start_date')
            ->get();

        // تجميع الأحداث حسب اليوم
        $eventsByDay = [];
        foreach ($events as $event) {
            $day = $event->start_date->format('Y-m-d');
            $eventsByDay[$day][] = $event;
        }

        return view('calendar.index', [
            'month'       => $month,
            'monthObj'    => $monthObj,
            'events'      => $events,
            'eventsByDay' => $eventsByDay,
            'types'       => CalendarEvent::types(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'start_time'  => 'nullable',
            'end_time'    => 'nullable',
            'type'        => 'required|in:general,session,campaign,birthday,reminder,other',
            'all_day'     => 'boolean',
        ]);

        $types = CalendarEvent::types();
        $data['color']      = $types[$data['type']]['color'];
        $data['created_by'] = auth()->id();
        $data['all_day']    = $request->boolean('all_day');

        CalendarEvent::create($data);

        return back()->with('success', 'تم إضافة الحدث بنجاح.');
    }

    public function destroy(CalendarEvent $event)
    {
        $event->delete();
        return back()->with('success', 'تم حذف الحدث.');
    }

    // API للداشبورد
    public function upcoming()
    {
        $events = CalendarEvent::where('start_date', '>=', now()->toDateString())
            ->where('start_date', '<=', now()->addDays(7)->toDateString())
            ->orderBy('start_date')
            ->get();

        return response()->json($events);
    }
}