<?php

namespace App\Http\Controllers;

use App\Models\JobWordCount;
use Illuminate\Http\Request;

class JobWordCountController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Word Count')) {
            $jobWordCounts = JobWordCount::where('created_by', \Auth::user()->creatorId())->get();
            return view('ConfigWordCount.index', compact('jobWordCounts'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit(JobWordCount $jobWordCount)
    {
        $jobWordCount = $jobWordCount->first();
        if (\Auth::user()->can('Edit Word Count')) {
            if ($jobWordCount->created_by == \Auth::user()->creatorId()) {
                return view('ConfigWordCount.edit', compact('jobWordCount'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, JobWordCount $jobWordCount)
    {
        $jobWordCount = $jobWordCount->first();
        if (\Auth::user()->can('Edit Word Count')) {
            if ($jobWordCount->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'limit' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $jobWordCount->limit = $request->limit;
                $jobWordCount->save();

                return redirect()->route('config-word-count.index')->with('success', __('Word count successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
