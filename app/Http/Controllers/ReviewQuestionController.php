<?php

namespace App\Http\Controllers;

use App\Models\ReviewQuestion;
use Illuminate\Http\Request;

class ReviewQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($review_id)
    {
        return view('employeereviews.create_question',compact('review_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$review_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'question'         => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

       $question=new ReviewQuestion();
       $question->review_id=$review_id;
       $question->question=$request->question;
       $question->save();
       return redirect()->route('employeereviews.show',$review_id)->with('success', __('Question successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewQuestion $reviewQuestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reviewquestion=ReviewQuestion::find($id);
        $review_id=$reviewquestion->review_id;
        return view('employeereviews.edit_question',compact('reviewquestion','review_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, $review_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'question'         => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

       $question=ReviewQuestion::find($id);
       $question->question=$request->question;
       $question->save();
       return redirect()->route('employeereviews.show',$review_id)->with('success', __('Question successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id,$review_id)
    {
        $question=ReviewQuestion::find($id);
        $question->delete();
        return redirect()->route('employeereviews.show',$review_id)->with('success', __('Question successfully deleted.'));
    }
}
