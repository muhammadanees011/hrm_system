<?php

namespace App\Http\Controllers;

use App\Models\MeetingTemplatePoint;
use Illuminate\Http\Request;

class MeetingTemplatePointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($template_id)
    {
        return view('meetingtemplatepoint.create',compact('template_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$template_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'         => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $meetingtemplate                      = new MeetingTemplatePoint();
        $meetingtemplate->meeting_template_id = $template_id;
        $meetingtemplate->title               = $request->title;
        $meetingtemplate->created_by          = \Auth::user()->creatorId();
        $meetingtemplate->save();

        return redirect()->route('meetingtemplate.show',$template_id)->with('success', __('Point successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingTemplatePoint $meetingTemplatePoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($template_id,$point_id)
    {
        $meetingtemplatepoint = MeetingTemplatePoint::find($point_id);
        return view('meetingtemplatepoint.edit',compact('meetingtemplatepoint','template_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $template_id,$point_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'         => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $meetingtemplatepoint                      = MeetingTemplatePoint::find($point_id);
        $meetingtemplatepoint->title               = $request->title;
        $meetingtemplatepoint->save();

        return redirect()->route('meetingtemplate.show',$template_id)->with('success', __('Point successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($template_id,$point_id)
    {
        $meetingtemplatepoint=MeetingTemplatePoint::find($point_id);
        $meetingtemplatepoint->delete();
        return redirect()->route('meetingtemplate.show',$template_id)->with('success', __('Point successfully deleted.'));
    }
}
