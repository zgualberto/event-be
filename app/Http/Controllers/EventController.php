<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Event;
use App\Http\Resources\Event as EventResource;
use DateTime;
use DateInterval;
use DatePeriod;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EventResource::collection(Event::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event;

        $event->title = $request->input('title');
        $event->edate = $request->input('edate');

        if($event->save()) {
            return new EventResource($event);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get event
        $event = Event::findOrFail($id);

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get event
        $event = Event::findOrFail($id);

        $event->title = $request->input('title');
        $event->edate = $request->input('edate');

        if($event->save()) {
            return new EventResource($event);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get event
        $event = Event::findOrFail($id);

        if($event->delete()) {
            return new EventResource($event);
        }
    }

    /**
     * Create multiple event based on date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createMultipleEvent(Request $request)
    {
        $events = [];

        $start    = new DateTime($request->input('dateFrom'));
        $start->modify('first day of this month');
        $end      = new DateTime($request->input('dateTo'));
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        // Remove existing event based on date range
        Event::whereDate('edate', '>=', $request->input('dateFrom'))
            ->OrWhereDate('edate', '<=', $request->input('dateTo'))
            ->delete();

        foreach ($period as $dt) {
            foreach ($request->input('days') as $key => $day) {
                $date = new DateTime('first '.$day.' of '.$dt->format("Y-m"));
                $thisMonth = $date->format('m');
    
                while ($date->format('m') === $thisMonth) {
                    if(
                        strtotime($date->format('Y-m-d')) >= strtotime($request->input('dateFrom')) &&
                        strtotime($date->format('Y-m-d')) <= strtotime($request->input('dateTo'))
                        ) {
                            array_push($events, [
                                "title" => $request->input('title'),
                                "edate" => $date->format('Y-m-d'),
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s')
                            ]);
                        }
                    $date->modify('next '.$day);
                }
            }
        }

        Event::insert($events);
        return EventResource::collection(
            Event::whereMonth('edate', intval($request->input('curMon')))
                ->whereYear('edate', $request->input('curYear'))
                ->get()
        );
    }

    /**
     * Display all current events based on month and year.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCurMonthEvents(Request $request)
    {
        return EventResource::collection(
            Event::whereMonth('edate', intval($request->input('curMon')))
                ->whereYear('edate', $request->input('curYear'))
                ->get()
        );
    }
}
