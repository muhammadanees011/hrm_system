<?php

namespace App\Http\Controllers;

use App\Models\AttendanceEmployee;
use App\Models\HolidayCarryOver;
use App\Models\HolidayConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayCarryOverController extends Controller
{
    public function index(Request $request)
    {
        if (\Auth::user()->can('Manage Holiday CarryOver')) {
            $holidayConfig = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();

            // calculating available entitlement
            $startDate = Carbon::parse($holidayConfig->annual_renew_date)->startOfDay();
            $endDate = Carbon::today();
            $availableEntitlement = 0;
            $totalApprovedCarryOverDaysThisYear = 0;
            $totalApprovedCarryOverDays = 0;
            $carryOversCount = 0;
            $totalApproved = 0;
            $totalRejected = 0;

            if (\Auth::user()->type == 'employee') {
                $holidayCarryOvers = HolidayCarryOver::where('created_by', '=', \Auth::user()->creatorId())->where('user_id', auth()->id());
                $attendanceRecords = AttendanceEmployee::where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->where('employee_id', auth()->user()->employee->id)
                    ->where('status', 'Present')
                    ->count();
                $totalApprovedDays = auth()->user()->holidays()
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->where('status', 'Approved')
                    ->sum('total_days');

                $totalApprovedCarryOverDaysThisYear = auth()->user()->holidayCarryOvers()
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->where('status', 'Approved')
                    ->sum('total_days');

                $previousRenewStartDate = Carbon::parse($holidayConfig->annual_renew_date)->subYear()->startOfDay();
                $previousRenewEndDate = $startDate->copy()->subDay();

                $totalApprovedCarryOverDays = auth()->user()->holidayCarryOvers()
                    ->whereDate('created_at', '>=', $previousRenewStartDate)
                    ->whereDate('created_at', '<=', $previousRenewEndDate)
                    ->where('status', 'Approved')
                    ->sum('total_days');

                $totalCarryOvers = abs($totalApprovedCarryOverDaysThisYear - $totalApprovedCarryOverDays);



                $availableEntitlement = intval(round($holidayConfig->annual_entitlement * ($attendanceRecords / $holidayConfig->total_annual_working_days))) - ($totalApprovedDays + $totalCarryOvers);
            } else {
                $holidayCarryOvers = HolidayCarryOver::where('created_by', '=', \Auth::user()->creatorId());
            }

            $holidayCarryOvers = $holidayCarryOvers->get();
            $totalApproved = $holidayCarryOvers->where('status', 'Approved')->count();
            $totalRejected = $holidayCarryOvers->where('status', 'Rejected')->count();
            $carryOversCount = $holidayCarryOvers->count();
            
            return view('holidayCarryOver.index', compact('holidayCarryOvers', 'availableEntitlement', 'totalApprovedCarryOverDaysThisYear', 'totalApprovedCarryOverDays', 'carryOversCount', 'totalApproved', 'totalRejected'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Holiday CarryOver')) {
            return view('holidayCarryOver.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Holiday CarryOver')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'total_days' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holidayConfig = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();
            // calculating available entitlement
            $startDate = Carbon::parse($holidayConfig->annual_renew_date)->startOfDay();
            $endDate = Carbon::today();

            $totalDays = $request->total_days;

            $attendanceRecords = AttendanceEmployee::where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->where('employee_id', auth()->user()->employee->id)
                ->where('status', 'Present')
                ->count();

            $totalApprovedDays = auth()->user()->holidays()
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->where('status', 'Approved')
                ->sum('total_days');

            $totalApprovedCarryOverDaysThisYear = auth()->user()->holidayCarryOvers()
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->where('status', 'Approved')
                ->sum('total_days');

            $previousRenewStartDate = Carbon::parse($holidayConfig->annual_renew_date)->subYear()->startOfDay();
            $previousRenewEndDate = $startDate->copy()->subDay();

            $totalApprovedCarryOverDays = auth()->user()->holidayCarryOvers()
                ->whereDate('created_at', '>=', $previousRenewStartDate)
                ->whereDate('created_at', '<=', $previousRenewEndDate)
                ->where('status', 'Approved')
                ->sum('total_days');

            $totalCarryOvers = abs($totalApprovedCarryOverDaysThisYear - $totalApprovedCarryOverDays);

            // dd($totalCarryOvers);


            // $availableEntitlement = intval(round($holidayConfig->annual_entitlement * ($attendanceRecords / $holidayConfig->total_annual_working_days))) - ($totalApprovedDays + $totalCarryOvers);
            // if ($totalDays > $availableEntitlement) {
            //     return redirect()->back()->with('error', 'Please request the holiday carry-overs as per available holiday entitlement');
            // }

            // if (Carbon::parse($holidayConfig->annual_renew_date) > $todayDate) {
            //     return redirect()->back()->with('error', 'Please request the holidays in next year range');
            // }

            $holiday             = new HolidayCarryOver();
            $holiday->total_days          = $totalDays;
            $holiday->user_id          = \Auth::user()->id;
            $holiday->status          = 'Pending';
            $holiday->created_by = \Auth::user()->creatorId();
            $holiday->save();

            return redirect()->route('holiday-carryover.index')->with('success', 'Holiday CarryOver successfully created.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit Holiday CarryOver')) {
            $holidayCarryOver = HolidayCarryOver::where('id', $id)->first();
            if ($holidayCarryOver->created_by == \Auth::user()->creatorId()) {
                return view('holidayCarryOver.edit', compact('holidayCarryOver'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Holiday CarryOver')) {
            $holidayCarryover = HolidayCarryOver::where('id', $id)->first();
            if ($holidayCarryover->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'total_days' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $holidayConfig = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();
                // calculating available entitlement
                $startDate = Carbon::parse($holidayConfig->annual_renew_date)->startOfDay();
                $endDate = Carbon::today();

                $totalDays = $request->total_days;

                $attendanceRecords = AttendanceEmployee::where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->where('employee_id', $holidayCarryover->user_id)
                    ->where('status', 'Present')
                    ->count();


                $totalApprovedDays = auth()->user()->holidays()
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->where('status', 'Approved')
                    ->sum('total_days');

                $totalApprovedCarryOverDaysThisYear = auth()->user()->holidayCarryOvers()
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->where('status', 'Approved')
                    ->sum('total_days');

                $previousRenewStartDate = Carbon::parse($holidayConfig->annual_renew_date)->subYear()->startOfDay();
                $previousRenewEndDate = $startDate->copy()->subDay();

                $totalApprovedCarryOverDays = auth()->user()->holidayCarryOvers()
                    ->whereDate('created_at', '>=', $previousRenewStartDate)
                    ->whereDate('created_at', '<=', $previousRenewEndDate)
                    ->where('status', 'Approved')
                    ->sum('total_days');

                $totalCarryOvers = abs($totalApprovedCarryOverDaysThisYear - $totalApprovedCarryOverDays);

                // dd($totalCarryOvers);


                $availableEntitlement = intval(round($holidayConfig->annual_entitlement * ($attendanceRecords / $holidayConfig->total_annual_working_days))) - ($totalApprovedDays + $totalCarryOvers);

                if ($holidayCarryover->total_days < $totalDays && $totalDays > $availableEntitlement) {
                    return redirect()->back()->with('error', 'Please request the holiday carry-overs as per available holiday entitlement');
                }

                // if (Carbon::parse($holidayConfig->annual_renew_date) > $todayDate) {
                //     return redirect()->back()->with('error', 'Please request the holidays in next year range');
                // }

                $holidayCarryover->total_days       = $request->total_days;
                $holidayCarryover->save();

                return redirect()->route('holiday-carryover.index')->with('success', __('Holiday carry over successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateStatus(Request $request, $holidayId)
    {
        $holiday = HolidayCarryOver::where('id', $holidayId)->first();
        $holiday->status = $request->status;
        $holiday->save();

        return redirect()->route('holiday-carryover.index')->with(
            'success',
            'Holiday CarryOver successfully updated.'
        );
    }
}
