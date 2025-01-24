<?php
namespace App\Http\Controllers;

use App\Models\BenefitsRequest;
use App\Models\BenefitsScheme;
use Illuminate\Http\Request;

class BenefitsRequestController extends Controller
{
    public function create()
    {
        $benefits = BenefitsScheme::all();
        return view('benefitsRequest.create', compact('benefits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'benefit_id' => 'required',
            'reason' => 'required',
        ]);

        BenefitsRequest::create([
            'employee_id' => \Auth::user()->employee->id,
            'benefit_id' => $request->benefit_id,
            'reason' => $request->reason,
        ]);

        return redirect()->route('benefitsRequest.index')->with('success', 'Benefits request submitted successfully.');
    }

    public function index()
    {
        if (\Auth::user()->type == 'hr' || \Auth::user()->type == 'company') {
           
            // Admin and HR can see all requests
            $requests = BenefitsRequest::with('employee', 'benefit')->get();
        } else {
            // Employees can only see their own requests
            $requests = BenefitsRequest::with('employee', 'benefit')
                ->where('employee_id', \Auth::user()->employee->id)
                ->get();
        }

        return view('benefitsRequest.index', compact('requests'));
    }

    public function approve($id)
    {
        $request = BenefitsRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return redirect()->route('benefitsRequest.index')->with('success', 'Benefits request approved successfully.');
    }

    public function reject($id)
    {
        $request = BenefitsRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return redirect()->route('benefitsRequest.index')->with('success', 'Benefits request rejected successfully.');
    }
}