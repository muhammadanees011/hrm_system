<?php

namespace App\Http\Controllers;

use App\Models\CaseCategory;
use Illuminate\Http\Request;

class CaseCategoryController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Case Category')) {
            $categories = CaseCategory::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('caseCategory.index', compact('categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Case Category')) {
            return view('caseCategory.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Case Category')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $caseCategory             = new CaseCategory();
            $caseCategory->title       = $request->title;
            $caseCategory->created_by = \Auth::user()->creatorId();
            $caseCategory->save();

            return redirect()->route('case-category.index')->with('success', __('Case Category  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(CaseCategory $caseCategory)
    {
        return redirect()->route('case-category.index', compact('caseCategory'));
    }

    public function edit(CaseCategory $caseCategory)
    {
        if (\Auth::user()->can('Edit Case Category')) {
            if ($caseCategory->created_by == \Auth::user()->creatorId()) {

                return view('caseCategory.edit', compact('caseCategory'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, CaseCategory $caseCategory)
    {
        if (\Auth::user()->can('Edit Case Category')) {
            if ($caseCategory->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $caseCategory->title = $request->title;
                $caseCategory->save();

                return redirect()->route('case-category.index')->with('success', __('Case Category successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(CaseCategory $caseCategory)
    {
        if (\Auth::user()->can('Delete Case Category')) {
            $caseCategory->delete();
            return redirect()->route('case-category.index')->with('success', __('Case Category successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
