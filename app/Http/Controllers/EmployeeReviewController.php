<?php

namespace App\Http\Controllers;

use App\Models\EmployeeReview;
use App\Models\ReviewHasReviewee;
use App\Models\ReviewHasReviewr;
use App\Models\ReviewHasResult;
use App\Models\PerformanceCycle;
use App\Models\ReviewQuestion;
use Spatie\Permission\Models\Role;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(\Auth::user()->can('Manage Reviews'))
        // {
            $user=\Auth::user();
            if($user->type=='employee'){
                $employee_reviews=ReviewHasReviewr::where('user_id',$user->id)->pluck('review_id')->toArray();
                $reviews=EmployeeReview::whereIn('id',$employee_reviews)
                ->with('performanceCycle')
                ->withCount('reviewers', 'reviewees')
                ->withCount(['reviewees as completed_reviews_count' => function ($query) {
                    $query->whereColumn('buddy_reviewers', 'buddy_reviews')
                        ->whereColumn('management_reviewers', 'management_reviews');
                }])
                ->get();
            }else{
                $reviews = EmployeeReview::with('performanceCycle')
                ->withCount('reviewers', 'reviewees')
                ->withCount(['reviewees as completed_reviews_count' => function ($query) {
                    $query->whereColumn('buddy_reviewers', 'buddy_reviews')
                        ->whereColumn('management_reviewers', 'management_reviews');
                }])
                ->get();

            }
            return view('employeereviews.index',compact('reviews'));
        // }else{
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reviewer = User::where('type','!=','company')->get()->pluck('name', 'id');
        $performancecycles = PerformanceCycle::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        return view('employeereviews.create',compact('reviewer','performancecycles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'         => 'required',
                'status'        => 'required',
                'who_can_review' => 'required',
                'reviewer'      => 'nullable',
                'performancecycle_id'  => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $employeereview                      = new EmployeeReview();
        $employeereview->title               = $request->title;
        $employeereview->performancecycle_id = $request->performancecycle_id;
        $employeereview->who_can_review      = $request->who_can_review;
        $employeereview->status              = $request->status;
        $employeereview->created_by          = \Auth::user()->creatorId();
        $employeereview->save();

        if($request->who_can_review=='select')
        {
            foreach($request->reviewer as $item){
                $reviewer =new ReviewHasReviewr();
                $reviewer->review_id = $employeereview->id;
                $reviewer->user_id = $item;
                $reviewer->save();
            }
        }
        else if($request->who_can_review=='all')
        {
           $users =User::where('type','!=','company')->get()->pluck('id')->toArray();
           foreach($users as $item){
                $reviewer =new ReviewHasReviewr();
                $reviewer->review_id = $employeereview->id;
                $reviewer->user_id = $item;
                $reviewer->save();
            }
        }
        else if($request->who_can_review != 'select' && $request->who_can_review != 'all')
        {
           $users =User::where('type',$request->who_can_review)->get()->pluck('id')->toArray();
           foreach($users as $item){
                $reviewer =new ReviewHasReviewr();
                $reviewer->review_id = $employeereview->id;
                $reviewer->user_id = $item;
                $reviewer->save();
            }
        }

        return redirect()->route('employeereviews.index')->with('success', __('Review successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeReview $employeereview)
    {
        $roles = Role::get()->pluck('name', 'id');
        $reviewees = User::where('type','!=','company')->get()->pluck('name', 'id');
        $review_questions = ReviewQuestion::where('review_id',$employeereview->id)->get();
        return view('employeereviews.show',compact('review_questions','employeereview','roles','reviewees'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeReview $employeereview)
    {
        $reviewer = User::where('type','!=','company')->get()->pluck('name', 'id');
        $performancecycles = PerformanceCycle::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        return view('employeereviews.edit',compact('employeereview','reviewer','performancecycles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeReview $employeereview)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'         => 'required',
                'status'        => 'required',
                'who_can_review' => 'required',
                'reviewer'      => 'nullable',
                'performancecycle_id'  => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $check=EmployeeReview::find($employeereview->id);
        if($check->who_can_review != $request->who_can_review)
        {
            $reviewer=ReviewHasReviewr::where('review_id',$employeereview->id)->delete();
        }

        $employeereview->title               = $request->title;
        $employeereview->performancecycle_id = $request->performancecycle_id;
        $employeereview->who_can_review      = $request->who_can_review;
        $employeereview->status              = $request->status;
        $employeereview->created_by          = \Auth::user()->creatorId();
        $employeereview->save();

        if($request->who_can_review=='select')
        {
            ReviewHasReviewr::where('review_id',$employeereview->id)->delete();
            foreach($request->reviewer as $item){
                $reviewer =new ReviewHasReviewr();
                $reviewer->review_id = $employeereview->id;
                $reviewer->user_id = $item;
                $reviewer->save();
            }
        }
        else if($request->who_can_review=='all')
        {
           ReviewHasReviewr::where('review_id',$employeereview->id)->delete();
           $users =User::where('type','!=','company')->get()->pluck('id')->toArray();
           foreach($users as $item){
                $reviewer =new ReviewHasReviewr();
                $reviewer->review_id = $employeereview->id;
                $reviewer->user_id = $item;
                $reviewer->save();
            }
        }
        else if($request->who_can_review != 'select' && $request->who_can_review != 'all')
        {
           ReviewHasReviewr::where('review_id',$employeereview->id)->delete();
           $users =User::where('type',$request->who_can_review)->get()->pluck('id')->toArray();
           foreach($users as $item){
                $reviewer =new ReviewHasReviewr();
                $reviewer->review_id = $employeereview->id;
                $reviewer->user_id = $item;
                $reviewer->save();
            }
        }

        return redirect()->route('employeereviews.index')->with('success', __('Review successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review=EmployeeReview::find($id);
        $review->delete();
        return redirect()->route('employeereviews.index')->with('success', __('Review successfully deleted.'));
    }

    public function completeReview($id)
    {
        $review=EmployeeReview::find($id);
        if($review->status != 'Completed'){
            $review->status='Completed';
            $review->save();
            return redirect()->route('employeereviews.show',$id)->with('success', __('Review successfully completed.'));
        }else{
            return redirect()->route('employeereviews.show',$id)->with('error', __('Review Already completed.'));
        }
    }

    public function updateReviewees(Request $request,$id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'who_to_review' => 'required',
                'reviewees'      => 'nullable',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $review=EmployeeReview::find($id);
        $review->who_to_review=$request->who_to_review;
        $review->save();

        if($request->who_to_review == 'select'){
            ReviewHasReviewee::where('review_id',$id)->delete();
            foreach($request->reviewees as $item){
                $reviewee =new ReviewHasReviewee();
                $reviewee->review_id = $id;
                $reviewee->user_id   = (int)$item;
                $reviewee->save();
            }
        }
        if($request->who_to_review == 'all'){
            $users =User::where('type','!=','company')->get()->pluck('id')->toArray();
            ReviewHasReviewee::where('review_id',$id)->delete();
            foreach($users as $item){
                $reviewee =new ReviewHasReviewee();
                $reviewee->review_id = $id;
                $reviewee->user_id   = $item;
                $reviewee->save();
            }
        }
        else if($request->who_to_review != 'select' && $request->who_to_review != 'all')
        {
            ReviewHasReviewee::where('review_id',$id)->delete();
            $users =User::where('type',$request->who_to_review)->get()->pluck('id')->toArray();
            foreach($users as $item){
                $reviewee =new ReviewHasReviewee();
                $reviewee->review_id = $id;
                $reviewee->user_id   = $item;
                $reviewee->save();
            }
        }
        return redirect()->route('employeereviews.show',$id)->with('success', __('Review successfully updated.'));

    }

    public function revieweesList($id)
    {
        $user=\Auth::user();
        if($user->type=="employee"){
            $reviewers=ReviewHasReviewr::where(['review_id'=>$id,'user_id'=>$user->id])->first();
            if($reviewers){
                $reviewees=ReviewHasReviewee::where(['review_id'=>$id])->get();
            }else{
                $reviewees=ReviewHasReviewee::where(['review_id'=>$id,'user_id'=>$user->id,])->get();                
            }
        }else{
            $reviewees=ReviewHasReviewee::where('review_id',$id)->get();
        }
        $reviewers=ReviewHasReviewr::where('review_id',$id)->get();

        $manager_count = 0;
        $buddy_count = 0;
        foreach ($reviewers as $reviewer) {
            if ($reviewer->user->type === 'manager') {
                $manager_count++;
            } elseif ($reviewer->user->type === 'employee') {
                $buddy_count++;
            }
        }
        ReviewHasReviewee::where('review_id',$id)->update([
            'buddy_reviewers'=>$buddy_count,
            'management_reviewers'=>$manager_count
        ]);
        
        return view('employeereviews.reviewees',compact('buddy_count','manager_count','reviewees'));

    }

    public function reviewQuestions($review_id, $user_id)
    {
        $reviewer_id=\Auth::user()->id;
        $reviewquestions = ReviewQuestion::with(['reviewResult' => function ($query) use ($user_id,$reviewer_id) {
            $query->where(['reviewee_id'=> $user_id, 'reviewer_id'=>$reviewer_id]);
        }])->where('review_id', $review_id)->get();
        $user=User::where('id',$user_id)->first();
        $performancecycle_id=EmployeeReview::where('id',$review_id)->first('performancecycle_id');
        $performancecycle=PerformanceCycle::where('id',$performancecycle_id->performancecycle_id)->first();
        $employee=Employee::where('user_id',$user_id)->first('id');
        $review_results=ReviewHasResult::where(['review_id'=>$review_id,'reviewee_id'=>$user_id,'reviewer_id'=>$reviewer_id])->get();
        if ($user && $employee) {
            $user->employee_id = $employee->id;
        }
        $reviewee_id=$user_id;
        return view('employeereviews.review_questions',compact('review_results','review_id','reviewee_id','reviewquestions','user','performancecycle'));   
    }

    public function submit_review_result(Request $request)
    {
        $reviewer_id=\Auth::user()->id;

        $check=ReviewHasResult::where(['review_id'=>$request->review_id,'reviewee_id'=>$request->reviewee_id,'reviewer_id'=>$reviewer_id])->first();
        if(!$check){
            $user_type=User::where('id',$reviewer_id)->first('type');
            $update_reviewee=ReviewHasReviewee::where(['review_id'=>$request->review_id,'user_id'=>$request->reviewee_id])->first();
            if($user_type->type=='manager'){
                $update_reviewee->management_reviews=$update_reviewee->management_reviews +1;
            }
            if($user_type->type=='employee'){
                $update_reviewee->buddy_reviews=$update_reviewee->buddy_reviews +1;
            }
            $update_reviewee->save();

            $employee_review=EmployeeReview::where('id',$request->review_id)->first();
            $employee_review->completed_reviews= $employee_review->completed_reviews + 1;
            $employee_review->save();
        }

        foreach($request->selectedOptions as $option)
        {
            $question_id=$option['index'];
            $result=ReviewHasResult::where([
                'question_id' => $question_id,
                'reviewee_id' => $request->reviewee_id,
                'reviewer_id' => $reviewer_id,
                'review_id'   => $request->review_id
            ])->first();
            if($result)
            {
                $result->selected_option=$option['option'];
                $result->save();
            }else{             
            $result=new ReviewHasResult();
            $result->review_id=$request->review_id;
            $result->reviewer_id=$reviewer_id;
            $result->reviewee_id=$request->reviewee_id;
            $result->question_id=$option['index'];
            $result->selected_option=$option['option'];
            $result->save();

            }
        }
    }

    public function feedback($review_id, $user_id, $reviewer_id=null)
    {
        $reviewers=ReviewHasReviewr::where('review_id',$review_id)->get();
        if($reviewer_id)
        {
            $reviewquestions = ReviewQuestion::with(['reviewResult' => function ($query) use ($user_id,$reviewer_id) {
                $query->where(['reviewee_id'=> $user_id, 'reviewer_id' => $reviewer_id]);
            }])->where('review_id', $review_id)->get();
        }else{
            $default_reviewer=$reviewers[0]->user_id;
            $reviewquestions = ReviewQuestion::with(['reviewResult' => function ($query) use ($user_id,$default_reviewer) {
                $query->where(['reviewee_id'=> $user_id,'reviewer_id'=>$default_reviewer]);
            }])->where('review_id', $review_id)->get();
        }
        $user=User::where('id',$user_id)->first();
        $performancecycle_id=EmployeeReview::where('id',$review_id)->first('performancecycle_id');
        $performancecycle=PerformanceCycle::where('id',$performancecycle_id->performancecycle_id)->first();
        $employee=Employee::where('user_id',$user_id)->first('id');
    
        if ($user && $employee) {
            $user->employee_id = $employee->id;
        }
        $reviewee_id=$user_id;
        return view('employeereviews.reviews_feedback',compact('reviewer_id','reviewers','review_id','reviewee_id','reviewquestions','user','performancecycle'));   
    }
}
