<?php

namespace App\Http\Controllers;

use App\Mail\NewMessageCaseDiscussion;
use App\Models\CaseDiscussion;
use App\Models\User;
use App\Models\VoiceCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CaseDiscussionController extends Controller
{
    public function index(Request $request)
    {
        if (\Auth::user()->can('View Discussion')) {
            $case = VoiceCase::where('uuid', $request->id)->with(['category', 'createdBy'])->first();
            if ($case->status != 'Investigating' && $case->representative == auth()->id()) {
                $case->status = 'Investigating';
                $case->save();
            }
            return view('caseDiscussion.index', compact('case'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Discussion')) {

            $isFile = false;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('file')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/caseDiscussion/';
                $file->storeAs($dir, $fileNameToStore);
                $fileName   = $fileNameToStore ? $fileNameToStore : null;
                $isFile = true;
            }
            CaseDiscussion::create([
                'case_code' => $request->case_code,
                'sender' => $request->sender,
                'receiver' => $request->receiver,
                'text' => $isFile ? null : $request->text,
                'file' => $isFile ? $fileName : null,
                'type' => $isFile ? 'file' : 'text',
            ]);

            $case = VoiceCase::where('uuid', $request->case_code)->first();
            $receiverEmail = User::where('id', $request->receiver)->first()->email;
            $email = new NewMessageCaseDiscussion($case);

            Mail::to($receiverEmail)->send($email);

            return redirect()->back()->with('success', __('Message send.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
