<?php

namespace App\Http\Controllers;

use App\Models\MeetingTemplate;
use App\Models\MeetingTemplatePoint;
use Illuminate\Http\Request;

class MeetingTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetingtemplates=MeetingTemplate::withCount('points')->get();
        return view('meetingtemplate.index',compact('meetingtemplates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('meetingtemplate.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'         => 'required',
                'description'        => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $meetingtemplate              = new MeetingTemplate();
        $meetingtemplate->title       = $request->title;
        $meetingtemplate->description = $request->description;
        $meetingtemplate->created_by  = \Auth::user()->creatorId();
        $meetingtemplate->save();

        return redirect()->route('meetingtemplate.index')->with('success', __('Template successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingTemplate $meetingtemplate)
    {
        $meetingtemplatepoints=MeetingTemplatePoint::where('meeting_template_id',$meetingtemplate->id)->get();
        return view('meetingtemplate.show',compact('meetingtemplate','meetingtemplatepoints'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeetingTemplate $meetingtemplate)
    {
        return view('meetingtemplate.edit',compact('meetingtemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeetingTemplate $meetingtemplate)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'         => 'required',
                'description'        => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $meetingtemplate->title       = $request->title;
        $meetingtemplate->description = $request->description;
        $meetingtemplate->save();

        return redirect()->route('meetingtemplate.index')->with('success', __('Template successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeetingTemplate $meetingtemplate)
    {
        $meetingtemplate->delete();
        return redirect()->route('meetingtemplate.index')->with('success', __('Template successfully updated.'));
    }
}
