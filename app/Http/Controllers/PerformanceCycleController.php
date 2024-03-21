<?php

namespace App\Http\Controllers;

use App\Models\PerformanceCycle;
use Illuminate\Http\Request;

class PerformanceCycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (\Auth::user()->can('Manage Performance')) {
            return view('performancecycle.index');
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
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
    public function show(PerformanceCycle $performanceCycle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceCycle $performanceCycle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceCycle $performanceCycle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceCycle $performanceCycle)
    {
        //
    }
}
