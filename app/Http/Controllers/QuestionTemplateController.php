<?php

namespace App\Http\Controllers;

use App\Models\CustomQuestion;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionTemplate;
use Illuminate\Http\Request;

class QuestionTemplateController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('Manage Question Template')) {
            $questions = QuestionTemplate::where('created_by', \Auth::user()->creatorId())->get();

            return view('questionTemplate.index', compact('questions'));
        } else {
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
        // dd($request);
        if (\Auth::user()->can('Create Question Template')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required|string|max:255',
                    'questions.*.name' => 'required|string|max:255',
                    'questions.*.type' => 'required|in:text,textarea,radio',
                    'questions.*.word_count' => 'nullable|integer|min:1',
                    'questions.*.options.*' => 'nullable|string|max:255',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            // Create a new QuestionTemplate
            $questionTemplate = QuestionTemplate::create([
                'title' => $request->input('title'),
                'created_by' => \Auth::user()->creatorId(),
            ]);

            // Loop through each question in the request
            foreach ($request->input('questions') as $questionData) {
                // Create a new Question
                $question = Question::create([
                    'name' => $questionData['name'],
                    'type' => $questionData['type'],
                    'word_count' => $questionData['word_count'],
                    'question_template_id' => $questionTemplate->id,
                ]);

                // If the question type is radio, create options
                if ($questionData['type'] === 'radio') {
                    foreach ($questionData['options'] as $optionText) {
                        QuestionOption::create([
                            'option_text' => $optionText,
                            'question_id' => $question->id,
                        ]);
                    }
                }
            }

            return redirect()->route('question-template.index')->with('success', __('Question successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(QuestionTemplate $questionTemplate)
    {
        //
    }

    public function edit(QuestionTemplate $questionTemplate)
    {
        return view('questionTemplate.edit', compact('questionTemplate'));
    }

    public function update(Request $request, QuestionTemplate $questionTemplate)
    {
        if (\Auth::user()->can('Edit Question Template')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'question' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $customQuestion->question    = $request->question;
            $customQuestion->is_required = $request->is_required;
            $customQuestion->save();

            return redirect()->back()->with('success', __('Question successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(QuestionTemplate $questionTemplate)
    {
        if (\Auth::user()->can('Delete Question Template')) {
            // Delete each question related to the template
            foreach ($questionTemplate->questions as $question) {
                // Delete each option related to the question
                foreach ($question->options as $option) {
                    $option->delete();
                }

                // Delete the question itself
                $question->delete();
            }

            // Delete the question template
            $questionTemplate->delete();

            return redirect()->back()->with('success', __('Question template successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function manageBranching($id)
    {
        if (\Auth::user()->can('Manage Branching')) {
            $questionTemplate = QuestionTemplate::where('id', $id)->first();
            return view('questionTemplate.branching', compact('questionTemplate'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function processBranchingLogic(Request $request, QuestionTemplate $questionTemplate)
    {
        if (\Auth::user()->can('Manage Branching')) {
            $branchingLogic = $request->input('branching');

            foreach ($branchingLogic as $optionId => $selectedBranchingLogic) {
                // Find the option by ID
                $option = QuestionOption::find($optionId);

                if ($option) {
                    // Associate the selected branching logic with the option
                    $option->branching_logic = $selectedBranchingLogic;
                    $option->save();
                }
            }
            return redirect()->route('question-template.index')->with('success', __('Brachning logic successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
