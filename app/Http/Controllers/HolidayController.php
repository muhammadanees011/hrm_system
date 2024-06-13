<?php

namespace App\Http\Controllers;

use App\Exports\HolidayExport;
use App\Imports\HolidayImport;
use App\Models\AttendanceEmployee;
use App\Models\Holiday as LocalHoliday;
use App\Models\HolidayConfiguration;
use App\Models\Utility;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Carbon\Carbon;


class HolidayController extends Controller
{

    public function index(Request $request)
    {
        $holidayPerMonth = 0;
        $monthsSinceStart = 0;
        $availableEntitlement = 0;

        if (\Auth::user()->can('Manage Holiday')) {
            $holidayConfig = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();
            $holidayPerMonth = round(($holidayConfig ? $holidayConfig->annual_entitlement : 0) / 12, 1);
            // calculating renew year since start interval
            $renewDate = new DateTime($holidayConfig ? $holidayConfig->annual_renew_date:'');
            $currentDate = new DateTime();
            $interval = $currentDate->diff($renewDate);
            $monthsSinceStart = $interval->m < 0 ? $interval->m . ' Month(s)' : $interval->d . ' Days';

            // calculating available entitlement
            $startDate = Carbon::parse($holidayConfig ? $holidayConfig->annual_renew_date :'')->startOfDay();
            $endDate = Carbon::today();

            if (\Auth::user()->type == 'employee') {
                $holidays = LocalHoliday::where('created_by', '=', \Auth::user()->creatorId())->where('user_id', \Auth::user()->id);
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


                $availableEntitlement = intval(round($holidayConfig->annual_entitlement * ($attendanceRecords / $holidayConfig->total_annual_working_days))) - ($totalApprovedDays + $totalCarryOvers);
            } else {
                $holidays = LocalHoliday::where('created_by', '=', \Auth::user()->creatorId());
            }



            if (!empty($request->start_date)) {
                $holidays->where('start_date', '>=', $request->start_date);
            }
            if (!empty($request->end_date)) {
                $holidays->where('end_date', '<=', $request->end_date);
            }
            $holidays = $holidays->get();
            $approvedHolidays = $holidays->where('status', 'Approved')->count();
            $rejectedHolidays = $holidays->where('status', 'Rejected')->count();

            return view('holiday.index', compact('holidays', 'holidayConfig', 'holidayPerMonth', 'monthsSinceStart', 'availableEntitlement', 'approvedHolidays', 'rejectedHolidays'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('Create Holiday')) {
            return view('holiday.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Holiday')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'occasion' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            // Logic to check employee is eligible for requesting holidays as per alotted one
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $totalDays = $end->diffInDays($start) + 1;

            $holidayConfig = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();
            // calculating available entitlement
            $startDate = Carbon::parse($holidayConfig->annual_renew_date)->startOfDay();
            $endDate = Carbon::today();

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


            $availableEntitlement = intval(round($holidayConfig->annual_entitlement * ($attendanceRecords / $holidayConfig->total_annual_working_days))) - ($totalApprovedDays + $totalCarryOvers);

            // if ($totalDays > $availableEntitlement) {
            //     return redirect()->back()->with('error', 'Please request the holidays as per available holiday entitlement');
            // }

            // if ($request->request_next_year && Carbon::parse($holidayConfig->annual_renew_date) > $start) {
            //     return redirect()->back()->with('error', 'Please request the holidays in next year range');
            // }

            $holiday             = new LocalHoliday();
            $holiday->occasion   = $request->occasion;
            $holiday->total_days = $totalDays;
            $holiday->user_id    = auth()->id();
            $holiday->occasion   = $request->occasion;
            $holiday->start_date = $request->start_date;
            $holiday->end_date   = $request->end_date;
            $holiday->isNextYear = $request->request_next_year;
            $holiday->status     = 'Pending';
            $holiday->created_by = \Auth::user()->creatorId();
            $holiday->save();

            // slack 
            $setting = Utility::settings(Auth::user()->creatorId());
            if (isset($setting['Holiday_notification']) && $setting['Holiday_notification'] == 1) {
                // $msg = $request->occasion . ' ' . __("on") . ' ' . $request->date . '.';

                $uArr = [
                    'occasion_name' => $request->occasion,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ];
                Utility::send_slack_msg('new_holidays', $uArr);
            }

            // telegram
            $setting = Utility::settings(\Auth::user()->creatorId());
            if (isset($setting['telegram_Holiday_notification']) && $setting['telegram_Holiday_notification'] == 1) {
                // $msg = $request->occasion . ' ' . __("on") . ' ' . $request->date . '.';

                $uArr = [
                    'occasion_name' => $request->occasion,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ];

                Utility::send_telegram_msg('new_holidays', $uArr);
            }

            // google calendar
            if ($request->get('synchronize_type')  == 'google_calender') {

                $type = 'holiday';
                $request1 = new GoogleEvent();
                $request1->title = $request->occasion;
                $request1->start_date = $request->start_date;
                $request1->end_date = $request->end_date;

                Utility::addCalendarData($request1, $type);
            }

            //webhook
            $module = 'New Holidays';
            $webhook =  Utility::webhookSetting($module);
            if ($webhook) {
                $parameter = json_encode($holiday);
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status == true) {
                    return redirect()->back()->with('success', __('Holiday successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($id)
    {
        $holidays = LocalHoliday::where('id', $id)->first();
        return view('holiday.show', compact('holidays'));
    }


    public function edit(LocalHoliday $holiday)
    {
        if (\Auth::user()->can('Edit Holiday')) {
            return view('holiday.edit', compact('holiday'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, LocalHoliday $holiday)
    {
        if (\Auth::user()->can('Edit Holiday')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'occasion' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            // Logic to check employee is eligible for requesting holidays as per alotted one
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $totalDays = $end->diffInDays($start) + 1;

            $holidayConfig = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();
            // calculating available entitlement
            $startDate = Carbon::parse($holidayConfig->annual_renew_date)->startOfDay();
            $endDate = Carbon::today();

            $attendanceRecords = AttendanceEmployee::where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->where('employee_id', $holiday->user_id)
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

            if ($holiday->total_days < $totalDays && $totalDays > $availableEntitlement) {
                return redirect()->back()->with('error', 'Please request the holidays as per available holiday entitlement');
            }


            $holiday->occasion          = $request->occasion;
            $holiday->total_days          = $totalDays;
            $holiday->start_date        = $request->start_date;
            $holiday->end_date          = $request->end_date;
            $holiday->save();

            return redirect()->route('holiday.index')->with(
                'success',
                'Holiday successfully updated.'
            );
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateStatus(Request $request, $holidayId)
    {
        $holiday = LocalHoliday::where('id', $holidayId)->first();
        $holiday->status = $request->status;
        $holiday->save();

        return redirect()->route('holiday.index')->with(
            'success',
            'Holiday successfully updated.'
        );
    }


    public function destroy(LocalHoliday $holiday)
    {
        if (\Auth::user()->can('Delete Holiday')) {
            $holiday->delete();

            return redirect()->route('holiday.index')->with(
                'success',
                'Holiday successfully deleted.'
            );
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // public function calender(Request $request)
    // {
    //     if (\Auth::user()->can('Manage Holiday')) {
    //         $holidays = LocalHoliday::where('created_by', '=', \Auth::user()->creatorId());
    //         $today_date = date('m');
    //         //  $current_month_event = Holiday::select( 'occasion','start_date','end_date', 'created_at')->whereRaw('MONTH(start_date)=' . $today_date,'MONTH(end_date)=' . $today_date)->get();
    //         $current_month_event = LocalHoliday::select('occasion', 'start_date', 'end_date', 'created_at')->whereNotNull(['start_date', 'end_date'])->whereMonth('start_date', $today_date)->whereMonth('end_date', $today_date)->get();

    //         if (!empty($request->start_date)) {
    //             $holidays->where('date', '>=', $request->start_date);
    //         }
    //         if (!empty($request->end_date)) {
    //             $holidays->where('date', '<=', $request->end_date);
    //         }
    //         $holidays = $holidays->get();

    //         $arrHolidays = [];

    //         foreach ($holidays as $holiday) {

    //             $arr['id']        = $holiday['id'];
    //             $arr['title']     = $holiday['occasion'];
    //             $arr['start']     = $holiday['start_date'];
    //             $arr['end']       = $holiday['end_date'];
    //             $arr['className'] = 'event-primary';
    //             $arr['url']       = route('holiday.edit', $holiday['id']);
    //             $arrHolidays[]    = $arr;
    //         }
    //         // $arrHolidays = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrHolidays)));
    //         $arrHolidays =  json_encode($arrHolidays);


    //         return view('holiday.calender', compact('arrHolidays', 'current_month_event'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission denied.'));
    //     }
    // }

    public function calender(Request $request)
    {
        if (\Auth::user()->can('Manage Holiday')) {
            $transdate = date('Y-m-d', time());

            $holidays = LocalHoliday::where('created_by', '=', \Auth::user()->creatorId());

            if (!empty($request->start_date)) {
                $holidays->where('start_date', '>=', $request->start_date);
            }
            if (!empty($request->end_date)) {
                $holidays->where('end_date', '<=', $request->end_date);
            }
            $holidays = $holidays->get();

            $arrHolidays = [];

            foreach ($holidays as $holiday) {
                $arr['id']        = $holiday['id'];
                $arr['title']     = $holiday['occasion'];
                $arr['start']     = $holiday['date'];
                $arr['end']       = $holiday['end_date'];
                $arr['className'] = 'event-primary';
                $arr['url']       = route('holiday.edit', $holiday['id']);
                $arrHolidays[]    = $arr;
            }
            $arrHolidays = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrHolidays)));

            return view('holiday.calender', compact('arrHolidays', 'transdate', 'holidays'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function export(Request $request)
    {
        $name = 'holidays_' . date('Y-m-d i:h:s');
        $data = Excel::download(new HolidayExport(), $name . '.xlsx');

        return $data;
    }
    public function importFile(Request $request)
    {
        return view('holiday.import');
    }
    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $holidays = (new HolidayImport())->toArray(request()->file('file'))[0];

        $totalholiday = count($holidays);

        $errorArray    = [];
        foreach ($holidays as $holiday) {


            $holiydayData = LocalHoliday::whereDate('start_date', $holiday['start_date'])->whereDate('end_date', $holiday['end_date'])->where('occasion', $holiday['occasion'])->first();

            if (!empty($holiydayData)) {
                $errorArray[] = $holiydayData;
            } else {
                $holidays_data = new LocalHoliday();
                $holidays_data->start_date = $holiday['start_date'];
                $holidays_data->end_date = $holiday['end_date'];
                $holidays_data->occasion = $holiday['occasion'];
                $holidays_data->created_by = Auth::user()->id;
                $holidays_data->save();
            }
        }


        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {

            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalholiday . ' ' . 'record');


            foreach ($errorArray as $errorData) {
                $errorRecord[] = implode(',', $errorData->toArray());
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function get_holiday_data(Request $request)
    {
        $arrayJson = [];
        if ($request->get('calender_type') == 'google_calender') {
            $type = 'holiday';
            $arrayJson =  Utility::getCalendarData($type);
        } else {
            $data = LocalHoliday::get();

            foreach ($data as $val) {
                if (Auth::user()->type == 'employee') {
                    $url = route('holiday.show', $val['id']);
                } else {
                    $url = route('holiday.edit', $val['id']);
                }
                $end_date = date_create($val->end_date);
                date_add($end_date, date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id" => $val->id,
                    "title" => $val->occasion,
                    "start" => $val->start_date,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => $val->color,
                    "textColor" => '#FFF',
                    "allDay" => true,
                    "url" => $url,
                ];
            }
        }

        return $arrayJson;
    }
}
