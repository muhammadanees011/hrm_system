<?php

namespace App\Http\Controllers;

use App\Models\Eclaim;
use App\Models\EclaimType;
use App\Models\Employee;
use App\Models\FlexiTime;
use App\Notifications\EclaimNotification;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
class FlexiTimeController extends Controller
{
    protected $hours = ["9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00"];

    public function index()
    {
        if (\Auth::user()->can('Manage FlexiTime')) {
            $query = new FlexiTime();
            if(\Auth::user()->type=="hr" && \Auth::user()->can('Approve FlexiTime')){
                $query = $query->where('status', 'pending');
            } else {
                $query = $query->where('created_by', \Auth::user()->id);
            }
            $records = $query->get();
            return view('flexiTime.index', compact('records'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create FlexiTime')) {
            $eClaimTypes = EclaimType::get()->pluck('title', 'id');
            $startDate = !empty($request->start_date) ? $request->input('start_date') : Carbon::now()->subDays(30)->toDateString();
            $endDate = !empty($request->end_date) ? $request->input('end_date') : Carbon::now()->toDateString();
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->first();
            } else {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            $hours = $this->hours;
            return view('flexiTime.create', compact('hours','employees', 'startDate', 'endDate'));   
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
{
    if (\Auth::user()->can('Create FlexiTime')) {

        $validator = \Validator::make(
            $request->all(),
            [
                'start_date' => 'required|string',
                'end_date' => 'required|string',
                'start_time' => 'required|string',
                'end_time' => 'required|string',
                'remark'=> 'required|string',
                'hours' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FlexiTime::create([
            'employee_id' => $request->employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours' => $request->hours,
            'remark' => $request->remark,
            'created_by' => \Auth::user()->id
        ]);

        return redirect()->route('flexi-time.index')->with('success', __('FlexiTime Request Generated Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function show(EclaimType $eclaim)
    {
        return redirect()->route('flexi-time.index');
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit FlexiTime')) {
            $eclaim = Eclaim::find($id);
            if ((\Auth::user()->type=="employee" && $eclaim->created_by == \Auth::user()->id) || $eclaim->created_by== \Auth::user()->creatorId()) {
                $eclaim = Eclaim::where('id', $id)->first();
                $eClaimTypes = EclaimType::get()->pluck('title', 'id');

                if (Auth::user()->type == 'employee') {
                    $employees = Employee::where('user_id', '=', \Auth::user()->id)->first();
                } else {
                    $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                }

                return view('eclaim.edit', compact('eclaim', 'eClaimTypes', 'employees'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Eclaim $eclaim)
{
    if (\Auth::user()->can('Edit FlexiTime')) {
        if ((\Auth::user()->type=="employee" && $eclaim->created_by == \Auth::user()->id) || $eclaim->created_by== \Auth::user()->creatorId()) {
            $eclaim_id =  $eclaim->id;
            $validator = \Validator::make(
                $request->all(),
                [
                    'type_id' => 'required',
                    'amount' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->hasFile('receipt')) {  
                    $file = $request->file('receipt');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = public_path() . '/eclaimreceipts';
                    $file->move($path,$fileName);
            }

            $history = [['time' => now(), 'message' => 'New Eclaim Requested Generated', 'comment' => '', 'username' => \Auth::user()->name]];
            $eClaimType               = Eclaim::find($eclaim_id);
            $eClaimType->type_id = $request->type_id;
            $eClaimType->amount = $request->amount;
            $eClaimType->description = $request->description;
            $eClaimType->receipt = $fileName ?? $eClaimType->receipt;
            $eClaimType->history = json_encode($history);
            $eClaimType->update();

            // Redirect to the appropriate route after updating
            return redirect()->route('eclaim.index')->with('success', __('Eclaim successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function destroy(Eclaim $eclaim)
    {
        if (\Auth::user()->can('Delete FlexiTime')) {
            if ((\Auth::user()->type=="employee" && $eclaim->created_by == \Auth::user()->id) || $eclaim->created_by== \Auth::user()->creatorId()) {
                $id =  $eclaim->id;
                $eclaim = Eclaim::find($id);
                $eclaim->delete();
                return redirect()->route('eclaim.index')->with('success', __('EclaimType successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showHistory(Eclaim $eclaim, $id)
    {
            $eclaim = Eclaim::find($id);
            $history = !empty($eclaim['history']) ? json_decode($eclaim['history'], true) : [];
            return view('eclaim.history', compact('history'));
    }
    public function showReceipt(Eclaim $eclaim, $id)
    {
            $eclaim = Eclaim::find($id);
            return view('eclaim.receipt', compact('FlexiTime'));
    }
    
    public function rejectForm(Request $request, $id){
        if (\Auth::user()->can('Manage FlexiTime')) {
            $eclaim = Eclaim::find($id);
            $url = "eclaim/save-reject-form/".$eclaim->id;
            return view('eclaim.comment-form', compact('url'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function saveRejectionForm(Request $request, $id){
        if (\Auth::user()->can('Manage FlexiTime')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'comment' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $eclaim = Eclaim::find($id);
            $history = json_decode($eclaim->history, true);
            $history[] = [
                'time' => now(),
                'username' => \Auth::user()->name,
                'message' => \Auth::user()->type=="hr" ? "Eclaim Rejected By HR Manager" : "Eclaim Rejected By Finance Manager",
                'comment' => $request->comment
            ];

            $eclaim->history = json_encode($history);
            $eclaim->status = "rejected";
            $eclaim->save();

            \Notification::route('mail', $eclaim->employee->email)
            ->notify(new EclaimNotification(['subject' => "Claim Request Rejection Notification", "message"=> "Your Claim Request got rejected. Please see below comment for detail information", "comment" => $request->comment]));

            return redirect()->route('eclaim.index')->with('success', __('Eclaim status successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function renderApprovalForm(Request $request, $id){
        if (\Auth::user()->can('Manage FlexiTime')) {
            $eclaim = Eclaim::find($id);
            $url = "eclaim/save-approval-form/".$eclaim->id;
            return view('eclaim.comment-form', compact('url'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function saveApprovalForm(Request $request, $id){
        if (\Auth::user()->can('Manage FlexiTime')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'comment' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $eclaim = Eclaim::with('employee')->find($id);
            $history = json_decode($eclaim->history, true);
            $message = \Auth::user()->type=="hr" ? "Eclaim Approved By HR Manager" : "Eclaim Approved By Finance Manager";
            $history[] = [
                'time' => now(),
                'username' => \Auth::user()->name,
                'message' => $message,
                'comment' => $request->comment
            ];

            $eclaim->history = json_encode($history);
            $eclaim->status = \Auth::user()->type=="hr" ? "approved by HR" : "approved";
            $eclaim->save();

            if(\Auth::user()->type !="hr"){
                \Notification::route('mail', $eclaim->employee->email)
                ->notify(new EclaimNotification(['subject' => "Claim Request Approval Notification", "message"=> "Your Claim Request got approved by the Finance Manager.", "comment" => $request->comment]));
            }

            return redirect()->route('eclaim.index')->with('success', __('Eclaim status successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function approve($id)
    {
        $FlexiTime = FlexiTime::find($id);
        $FlexiTime->status = "approved";
        $FlexiTime->update();
        return redirect()->route('flexi-time.index')->with('success', __('FlexiTime Request Updated Successfully.'));
    }
    public function reject($id)
    {
        $FlexiTime = FlexiTime::find($id);
        $FlexiTime->status = "rejected";
        $FlexiTime->update();
        return redirect()->route('flexi-time.index')->with('success', __('FlexiTime Request Updated Successfully.'));
    }
}
