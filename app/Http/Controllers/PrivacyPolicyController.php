<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $privacyPolicy = PrivacyPolicy::all();
        return view('privacy-policy.index', compact('privacyPolicy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('privacy-policy.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'requirement' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Save data to the database
        PrivacyPolicy::create([
            'name' => $request->input('name'),
            'requirement' => $request->input('requirement'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('privacy-policy.index')->with('success', 'Privacy policy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PrivacyPolicy $privacyPolicy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrivacyPolicy $privacyPolicy)
    {
        return view('privacy-policy.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrivacyPolicy $privacyPolicy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrivacyPolicy $privacyPolicy)
    {
        //
    }
}
