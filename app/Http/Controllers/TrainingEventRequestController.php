<?php

namespace App\Http\Controllers;

use App\Models\TrainingEventRequest;
use App\Models\TrainingEvent;
use App\Models\Notification;
use Illuminate\Http\Request;

class TrainingEventRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainingEventRequest=TrainingEventRequest::get();
        return view('trainingeventrequest.index', compact('trainingEventRequest'));
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
        $validator = \Validator::make(
            $request->all(),
            [
                'training_event_id' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $employee_id=\Auth::user()->employee->id;
        $check=TrainingEventRequest::where('employee_id',$employee_id)->where('training_event_id',$request->training_event_id)->first();
        if($check){
            return redirect()->back()->with('error', __('Request Already submitted.'));
        }
        $trainingevent=TrainingEvent::find($request->training_event_id);
        $eventrequestcount=TrainingEventRequest::where('training_event_id',$request->training_event_id)->count();

        if($trainingevent->participants_limit && $trainingevent->participants_limit==$eventrequestcount){
            return redirect()->back()->with('error', __('You can not proceed Limit reached.'));
        }
        $eventrequest=new TrainingEventRequest;
        $eventrequest->employee_id=$employee_id;
        $eventrequest->training_event_id=$request->training_event_id;
        $eventrequest->status='Pending';
        $eventrequest->save();

        $hrAndAdminUsers = \App\Models\User::whereIn('type', ['hr', 'company'])->get();
        foreach ($hrAndAdminUsers as $key => $hrAndAdminUser) {
            $notification=new Notification();
            $notification->sender_id=\Auth::user()->id;
            $notification->receiver_id=$hrAndAdminUser->id;
            $notification->title='New event perticipation request';
            $notification->body =\Auth::user()->name.' has submitted a new event perticipation request';
            $notification->read=false;
            $notification->save();
        }
        return redirect()->back()->with('success', __('Request successfully submitted.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingEventRequest $trainingEventRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trainingEventRequest=TrainingEventRequest::find($id);
        return view('trainingeventrequest.edit', compact('trainingEventRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'status' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $eventrequest= TrainingEventRequest::find($id);
        //---------Notification if status changed----------
        if($eventrequest->status!==$request->status){ 
            $notification=new Notification();
            $notification->sender_id=\Auth::user()->id;
            $notification->receiver_id=$eventrequest->employees->user_id;
            $notification->title='Your event perticipation request changed to '.$request->status;
            $notification->body =\Auth::user()->name.' has changed Your event perticipation request to '.$request->status.'';
            $notification->read=false;
            $notification->save();
        }
        $eventrequest->status=$request->status;
        $eventrequest->save();

        
      
        return redirect()->back()->with('success', __('Request status successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trainingEventRequest=TrainingEventRequest::find($id);
        $trainingEventRequest->delete();
        return redirect()->back()->with('success', __('Request successfully deleted.'));
    }
}
