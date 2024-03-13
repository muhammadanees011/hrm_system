<?php

namespace App\Http\Controllers;

use App\Models\CustomQuestion;
use Illuminate\Http\Request;

class QuestionTemplateController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('Manage Custom Question'))
        {
            $questions = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

            return view('questionTemplate.index');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $questions = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

        return view('questionTemplate.create', compact('questions'));
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Custom Question'))
        {
            return redirect()->back()->with('success', __('Question successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(CustomQuestion $customQuestion)
    {
        //
    }

    public function edit(CustomQuestion $customQuestion)
    {
        $is_required = CustomQuestion::$is_required;
        return view('customQuestion.edit', compact('customQuestion','is_required'));
    }

    public function update(Request $request, CustomQuestion $customQuestion)
    {
        if(\Auth::user()->can('Edit Custom Question'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'question' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $customQuestion->question    = $request->question;
            $customQuestion->is_required = $request->is_required;
            $customQuestion->save();

            return redirect()->back()->with('success', __('Question successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(CustomQuestion $customQuestion)
    {
        if(\Auth::user()->can('Delete Custom Question'))
        {
            $customQuestion->delete();

            return redirect()->back()->with('success', __('Question successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
