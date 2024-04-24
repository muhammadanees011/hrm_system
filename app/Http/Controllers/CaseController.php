<?php

namespace App\Http\Controllers;

use App\Models\CaseCategory;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use App\Models\VoiceCase;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Case')) {
            if (\Auth::user()->type == 'employee') {
                $cases = VoiceCase::where('created_by', '=', \Auth::user()->id)->get();
            } else {
                $cases = VoiceCase::where('representative', '=', \Auth::user()->id)->get();
            }

            return view('case.index', compact('cases'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Case')) {
            $categories = CaseCategory::get()->pluck('title', 'id');
            $representatives = User::where('created_by', '=', \Auth::user()->creatorId())->pluck('name', 'id');
            return view('case.create', compact('categories', 'representatives'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Case')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'category_id' => 'required',
                    'representative' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $case             = new VoiceCase();
            $case->title       = $request->title;
            $case->case_category_id       = $request->category_id;
            $case->uuid       = 'C#' .  uniqid();
            $case->representative       = $request->representative;
            $case->is_private       = $request->is_private ? true : null;
            $case->created_by = \Auth::user()->id;
            $case->save();

            return redirect()->route('case.index')->with('success', __('Case successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit Case')) {
            $voiceCase = VoiceCase::where('id', $id)->first();
            if ($voiceCase->created_by == \Auth::user()->creatorId()) {
                $categories = CaseCategory::get()->pluck('title', 'id');
                $representatives = Employee::where('created_by', '=', \Auth::user()->creatorId())->pluck('name', 'id');
                return view('case.edit', compact('voiceCase', 'categories', 'representatives'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Case')) {
            $voiceCase = VoiceCase::where('id', $id)->first();
            if ($voiceCase->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'category_id' => 'required',
                        'representative' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $voiceCase->title       = $request->title;
                $voiceCase->case_category_id       = $request->category_id;
                $voiceCase->representative       = $request->representative;
                $voiceCase->is_private       = $request->is_private ? true : null;
                $voiceCase->created_by = \Auth::user()->creatorId();
                $voiceCase->save();

                return redirect()->route('case.index')->with('success', __('Case successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('Delete Case')) {
            VoiceCase::where('id', $id)->first()->delete();
            return redirect()->route('case.index')->with('success', __('Case successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function caseClosed($id)
    {
        if (\Auth::user()->can('Edit Case')) {
            $case = VoiceCase::where('id', $id)->first();
            $case->status = 'Closed';
            $case->save();
            return redirect()->route('case.index')->with('success', __('Case successfully closed.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
