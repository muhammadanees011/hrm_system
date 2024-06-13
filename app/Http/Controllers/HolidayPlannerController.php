<?php

namespace App\Http\Controllers;

use App\Models\HolidayPlanner;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Leave as LocalLeave;
use App\Models\TrainingEvent as LocalEvent;

class HolidayPlannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user     = \Auth::user();
        $leaves = LocalLeave::with('leaveType')->get();
        $current_month_event=$leaves;
        $arrEvents=[];
        $employees=[];
        $events=$leaves;
        return view('holidayplanner.index', compact('arrEvents', 'employees','current_month_event','events'));
        
    }

    public function getHolidayPlanner(Request $request)
    {
        $user     = \Auth::user();
        $leaves = LocalLeave::with('leaveType')->get();
        
        $current_month_event=$leaves;
        $arrEvents=[];
        $employees=[];


        $arrayJson = [];
        if($request->get('calender_type') == 'google_calender')
        {
            $type ='event';
            $arrayJson =  Utility::getCalendarData($type);
        }
        else
        {
            $data = $leaves;

            foreach($data as $val)
            {
                $type=mb_substr($val->leaveType->title, 0, 1);
                $end_date=date_create($val->end_date);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->employees->name.'  ('.$type.')',
                    "start" => $val->start_date,
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => ($val->status=='Pending'? 'event-warning':($val->status=='Rejected'? 'event-danger':($val->status=='Approved'? 'event-success':''))),
                    "allDay" => true,
                     "url"=> null,
                    // "url"=> route('trainingevent.edit', $val['id']),

                ];
            }
        }
        
        return $arrayJson;
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HolidayPlanner $holidayPlanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HolidayPlanner $holidayPlanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HolidayPlanner $holidayPlanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HolidayPlanner $holidayPlanner)
    {
        //
    }
}
