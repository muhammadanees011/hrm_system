<?php

namespace App\Http\Controllers;

use App\Models\Eclaim;
use App\Models\EclaimType;
use App\Models\Employee;
use App\Notifications\EclaimNotification;
use Auth;
use Illuminate\Http\Request;
class EclaimController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Eclaim')) {
            $eclaims = Eclaim::with('claimType', 'employee')->where('created_by', '=', \Auth::user()->creatorId())->get();

            // if(\Auth::user()->type=="hr" && \Auth::user()->can('Approve Eclaim')){
            //     $query = $query->where('status', 'pending');
            // } else {
            //     $query = $query->where('status', 'approved by HR');
            // }

            // if(\Auth::user()->type=="employee"){
            //     $query = $query->where('employee_id', '=', \Auth::user()->id);
            // }else {
            //     $query = $query->where('created_by', '=', \Auth::user()->creatorId());
            // }
            // $eclaims = $query->get();
            return view('eclaim.index', compact('eclaims'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Eclaim')) {
            $eClaimTypes = EclaimType::get()->pluck('title', 'id');
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->first();
            } else {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            return view('eclaim.create', compact('eClaimTypes','employees'));   
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
{
    if (\Auth::user()->can('Create Eclaim')) {

        $validator = \Validator::make(
            $request->all(),
            [
                'employee_id' => 'required',
                'type_id' => 'required',
                'amount' => 'required',
                'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Assuming you're only allowing image files
                'description' => 'required',
            ]
        );
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path() . '/eclaimreceipts';
            $file->move($path,$fileName);
        }
        

        $history = [['time' => now(), 'message' => 'New Eclaim Requested Generated', 'username' => Auth::user()->name]];
        $eClaimType = new Eclaim();
        $eClaimType->type_id = $request->type_id;
        $eClaimType->amount = $request->amount;
        $eClaimType->description = $request->description;
        $eClaimType->receipt = $fileName ?? ''; // Assigning the filename if it exists, otherwise empty string
        $eClaimType->created_by = \Auth::user()->creatorId();
        $eClaimType->history = json_encode($history);
        $eClaimType->employee_id =  $request->employee_id;
        $eClaimType->save();

        return redirect()->route('eclaim.index')->with('success', __('Eclaim successfully created.'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function show(EclaimType $eclaim)
    {
        return redirect()->route('eclaim_type.index');
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit Eclaim')) {
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
    if (\Auth::user()->can('Edit Eclaim')) {
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
        if (\Auth::user()->can('Delete Eclaim')) {
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
            return view('eclaim.receipt', compact('eclaim'));
    }
    
    public function rejectForm(Request $request, $id){
        if (\Auth::user()->can('Manage Eclaim')) {
            $eclaim = Eclaim::find($id);
            $url = "eclaim/save-reject-form/".$eclaim->id;
            return view('eclaim.comment-form', compact('url'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function saveRejectionForm(Request $request, $id){
        if (\Auth::user()->can('Manage Eclaim')) {
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
        if (\Auth::user()->can('Manage Eclaim')) {
            $eclaim = Eclaim::find($id);
            $url = "eclaim/save-approval-form/".$eclaim->id;
            return view('eclaim.comment-form', compact('url'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function saveApprovalForm(Request $request, $id){
        if (\Auth::user()->can('Manage Eclaim')) {
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
}
