<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id=\Auth::user()->id;
        // $notifications=Notification::where('receiver_id',$user_id)->get();
        $notifications=Notification::with('sender','receiver')->get();
        return response()->json(['notifications' => $notifications]);
    }

    public function clearAll()
    {
        $user_id=\Auth::user()->id;
        $notifications=Notification::where('receiver_id',$user_id)->delete();
        return redirect()->back()->with('success', __('Notifications successfully cleared.'));
    }

    public function seenAll()
    {
        $user_id=\Auth::user()->id;
        Notification::where('receiver_id', $user_id)->update(['read' => true]);
        return redirect()->back()->with('success', __('Notifications successfully cleared.'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        
    }
}
