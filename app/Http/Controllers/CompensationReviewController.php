<?php

namespace App\Http\Controllers;

use App\Models\CompensationReview;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\CompensationReviewHasReviewees;
use App\Models\CompensationRevieweeHasReviewer;
use App\Models\CompensationRevieweeHasComment;

class CompensationReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews=CompensationReview::get();

        // if(\Auth::user()->type !='company'){
        //     // $reviews = CompensationReview::whereHas('reviewees', function ($query) {
        //     //     $query->where('reviewee_id', \Auth::id());
        //     // })->get();
        //     $currentUserId = \Auth::id();

            // $reviews = CompensationReview::whereHas('reviewees', function ($query) use ($currentUserId) {
            //     $query->where('reviewee_id', $currentUserId);
            // })->orWhereHas('reviewees.reviewer', function ($query) use ($currentUserId) {
            //     $query->where('reviewer_id', $currentUserId);
            // })->get();

        //      $reviews = CompensationReview::whereHas('reviewees', function ($query) use ($currentUserId) {
        //         $query->where('reviewee_id', $currentUserId);
        //     })->get();

        //     // $reviews = CompensationReview::whereHas('reviewees.reviewer', function ($query) use ($currentUserId) {
        //     //         $query->where('reviewer_id', $currentUserId);
        //     //     })->get();
        //     //     return $reviews;


            
        //     // return $reviews[0]->reviewees[0]->reviewer;


        // }else{
        //     $reviews=CompensationReview::get();
        // }
        return view('compensationreviews.index',compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reviewee = User::where('type','!=','company')->get()->pluck('name', 'id');
        return view('compensationreviews.create',compact('reviewee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Company Policy')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'recomended_increase_amount' => 'required',
                    'recomended_increase_percentage' => 'required',
                    'status' => 'required',
                    'who_is_reviewee' => 'required',                    
                    'reviewee' => 'nullable',
                    'status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $review                                       = new CompensationReview();
            $review->title                                = $request->title;
            $review->recomended_increase_amount           = $request->recomended_increase_amount;
            $review->recomended_increase_percentage       = $request->recomended_increase_percentage;
            $review->who_is_reviewee                      = $request->who_is_reviewee;
            $review->status                               = $request->status;
            $review->save();

            if($request->who_is_reviewee=='select')
            {
                foreach($request->reviewee as $item){
                    $reviewee =new CompensationReviewHasReviewees();
                    $reviewee->compensation_review_id = $review->id;
                    $reviewee->reviewee_id = $item;
                    $reviewee->save();
                }
            }
            else if($request->who_is_reviewee=='all')
            {
            $users =User::where('type','!=','company')->get()->pluck('id')->toArray();
            foreach($users as $item){
                    $reviewee =new CompensationReviewHasReviewees();
                    $reviewee->compensation_review_id = $review->id;
                    $reviewee->reviewee_id = $item;
                    $reviewee->save();
                }
            }
            else if($request->who_is_reviewee != 'select' && $request->who_can_review != 'all')
            {
            $users =User::where('type',$request->who_is_reviewee)->get()->pluck('id')->toArray();
            foreach($users as $item){
                    $reviewee =new CompensationReviewHasReviewees();
                    $reviewee->compensation_review_id = $review->id;
                    $reviewee->reviewee_id = $item;
                    $reviewee->save();
                }
            }

            return redirect()->route('compensationreview.index')->with('success', __('Compensation Review successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CompensationReview $compensationreview)
    {
        // return $compensationreview->reviewees;
        return view('compensationreviews.show',compact('compensationreview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompensationReview $compensationreview)
    {
        $reviewee = User::where('type','!=','company')->get()->pluck('name', 'id');
        return view('compensationreviews.edit',compact('reviewee','compensationreview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompensationReview $compensationreview)
    {
        if (\Auth::user()->can('Create Company Policy')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'recomended_increase_amount' => 'required',
                    'recomended_increase_percentage' => 'required',
                    'status' => 'required',
                    'who_is_reviewee' => 'required',                    
                    'reviewee' => 'nullable',
                    'status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $compensationreview->title                                = $request->title;
            $compensationreview->recomended_increase_amount           = $request->recomended_increase_amount;
            $compensationreview->recomended_increase_percentage       = $request->recomended_increase_percentage;
            $compensationreview->who_is_reviewee                      = $request->who_is_reviewee;
            $compensationreview->status                               = $request->status;
            $compensationreview->save();

            $check =CompensationReviewHasReviewees::where('compensation_review_id',$compensationreview->id)->get();
            if($check){
            CompensationReviewHasReviewees::where('compensation_review_id', $compensationreview->id)->delete();
            }

            if($request->who_is_reviewee=='select')
            {
                foreach($request->reviewee as $item){
                    $reviewee =new CompensationReviewHasReviewees();
                    $reviewee->compensation_review_id = $compensationreview->id;
                    $reviewee->reviewee_id = $item;
                    $reviewee->save();
                }
            }
            else if($request->who_is_reviewee=='all')
            {
            $users =User::where('type','!=','company')->get()->pluck('id')->toArray();
            foreach($users as $item){
                    $reviewee =new CompensationReviewHasReviewees();
                    $reviewee->compensation_review_id = $compensationreview->id;
                    $reviewee->reviewee_id = $item;
                    $reviewee->save();
                }
            }
            else if($request->who_is_reviewee != 'select' && $request->who_can_review != 'all')
            {
            $users =User::where('type',$request->who_is_reviewee)->get()->pluck('id')->toArray();
            foreach($users as $item){
                    $reviewee =new CompensationReviewHasReviewees();
                    $reviewee->compensation_review_id = $compensationreview->id;
                    $reviewee->reviewee_id = $item;
                    $reviewee->save();
                }
            }

            return redirect()->route('compensationreview.index')->with('success', __('Compensation Review successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompensationReview $compensationreview)
    {
        $compensationreview->delete();
        return redirect()->route('compensationreview.index')->with('success', __('Compensation Review successfully deleted.'));
    }

    public function editreviewee($id)
    {
        $compensationreviewee=CompensationReviewHasReviewees::find($id);
        $reviewer = User::where('type','!=','company')->get()->pluck('name', 'id');
        return view('compensationreviews.edit-reviewee',compact('reviewer','compensationreviewee'));   
    }

    public function updatereviewee(Request $request,$id)
    {
        if (\Auth::user()->can('Create Company Policy')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'recomended_increase_amount' => 'nullable',
                    'recomended_increase_percentage' => 'nullable',
                    'who_can_review' => 'nullable',                    
                    'reviewer' => 'nullable',
                    'eligible' => 'required',
                    'status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $compensationreviewee=CompensationReviewHasReviewees::find($id);
            $compensationreviewee->recomended_increase_amount             = $request->recomended_increase_amount;
            $compensationreviewee->recomended_increase_percentage         = $request->recomended_increase_percentage;
            $compensationreviewee->who_can_review                         = $request->who_can_review;
            $compensationreviewee->status                                 = $request->status;
            $compensationreviewee->eligible                               = $request->eligible;
            $compensationreviewee->save();

            if($request->status=='Approved'){
                $user_id=$compensationreviewee->reviewee_id;
                $employee=Employee::where('user_id',$user_id)->first();
                $comp_review=CompensationReview::find($compensationreviewee->review_id);
                $employee->salary=$employee->salary + ($employee->salary * (($compensationreviewee->recomended_increase_percentage ?? $comp_review->recomended_increase_percentage) / 100));
                $employee->save();

                $compensationreviewee->recomended_increase_percentage         = $request->status=='Approved' ? 0: $request->recomended_increase_percentage;
                $compensationreviewee->save();
            }

            if($request->who_can_review==null){
                return redirect()->route('compensationreview.show',$compensationreviewee->compensation_review_id)->with('success', __('Compensation Review successfully updated.'));
            }

            CompensationRevieweeHasReviewer::where('compensation_review_id',$compensationreviewee->compensation_review_id)
            ->where('reviewee_id',$compensationreviewee->reviewee_id)
            ->delete();

            if($request->who_can_review=='select')
            {
                foreach($request->reviewer as $item){
                    $reviewer =new CompensationRevieweeHasReviewer();
                    $reviewer->compensation_review_id = $compensationreviewee->compensation_review_id;
                    $reviewer->reviewee_id = $compensationreviewee->reviewee_id;
                    $reviewer->reviewer_id = $item;
                    $reviewer->save();
                }

            }
            else if($request->who_can_review=='all')
            {
            $users =User::where('type','!=','company')->get()->pluck('id')->toArray();
            foreach($users as $item){
                    $reviewer =new CompensationRevieweeHasReviewer();
                    $reviewer->compensation_review_id = $compensationreviewee->compensation_review_id;
                    $reviewer->reviewee_id = $compensationreviewee->reviewee_id;
                    $reviewer->reviewer_id = $item;
                    $reviewer->save();
                }
            }
            else if($request->who_can_review != 'select' && $request->who_can_review != 'all')
            {
            $users =User::where('type',$request->who_can_review)->get()->pluck('id')->toArray();
            foreach($users as $item){
                    $reviewer =new CompensationRevieweeHasReviewer();   
                    $reviewer->compensation_review_id = $compensationreviewee->compensation_review_id;
                    $reviewer->reviewee_id = $compensationreviewee->reviewee_id;
                    $reviewer->reviewer_id = $item;
                    $reviewer->save();
                }
            }

            return redirect()->route('compensationreview.show',$compensationreviewee->compensation_review_id)->with('success', __('Compensation Review successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }  
    }


    public function editcomments($reviewee_id, $review_id)
    {
        $comments=CompensationRevieweeHasComment::where('compensation_review_id',$review_id)
        ->where('reviewee_id',$reviewee_id)->get();
        return view('compensationreviews.comments',compact('comments','reviewee_id','review_id'));
    }

    public function updatecomments(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'review_id' => 'required',
                'reviewee_id' => 'required',
                'comment' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $commenter_id=\Auth::user()->id;
        $comment=new CompensationRevieweeHasComment();
        $comment->compensation_review_id=$request->review_id;
        $comment->reviewee_id=$request->reviewee_id;
        $comment->commenter_id=$commenter_id;
        $comment->comment=$request->comment;
        $comment->save();
        return redirect()->route('compensationreview.show',$request->review_id)->with('success', __('Comments successfully updated.'));
    }
}
