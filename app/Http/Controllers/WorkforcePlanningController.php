<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;

class WorkforcePlanningController extends Controller
{
    public function analytics()
    {
        if (\Auth::user()->can('View Analytics')) {
            $positions = Position::where('created_by', '=', \Auth::user()->creatorId())->with('departments')->get();
            // Group positions by department and count the positions in each department
            $positionsByDepartment = $positions->groupBy('departments.name')->map->count();

            // Group positions by status and count the positions in each status
            $positionsByStatus = $positions->groupBy('status')->map->count();

            // Group positions by job level and count the positions in each job level
            $positionsByJobLevel = $positions->groupBy('job_level')->map->count();

            // Group positions by branch and count the positions in each branch
            $positionsByBranch = $positions->groupBy('branches.name')->map->count();

            // Extract department names and counts
            $departments = $positionsByDepartment->keys()->toArray();
            $departmentCounts = $positionsByDepartment->values()->toArray();

            // Extract status and counts
            $status = $positionsByStatus->keys()->toArray();
            $statusCounts = $positionsByStatus->values()->toArray();

            // Extract job level and counts
            $jobLevel = $positionsByJobLevel->keys()->toArray();
            $jobLevelCounts = $positionsByJobLevel->values()->toArray();

            // Extract Branch and counts
            $branch = $positionsByBranch->keys()->toArray();
            $branchCounts = $positionsByBranch->values()->toArray();

            // Prepare data for the department chart
            $dataForDepartmentChart = [
                'labels' => $departments,
                'values' => $departmentCounts,
            ];

            // Prepare data for the status chart
            $dataForStatusChart = [
                'labels' => $status,
                'values' => $statusCounts,
            ];

            // Prepare data for the Job Level chart
            $dataForJobLevelChart = [
                'labels' => $jobLevel,
                'values' => $jobLevelCounts,
            ];

            // Prepare data for the branch chart
            $dataForBranchChart = [
                'labels' => $branch,
                'values' => $branchCounts,
            ];

            return view('workforce.analytics', compact('dataForDepartmentChart', 'dataForStatusChart', 'dataForJobLevelChart', 'dataForBranchChart'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function kpis()
    {
        if (\Auth::user()->can('View KPIs')) {
            $totalHeadcounts = Employee::where('employee_type', 'Permanent')->get()->count();
            $totalTargetHeadcounts = Position::get()->count();

            // Prepare data for the branch chart
            $dataForBranchChart = $this->prepareDataForBranchChart();



            return view('workforce.kpis', compact('totalHeadcounts', 'totalTargetHeadcounts', 'dataForBranchChart'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function prepareDataForBranchChart()
    {
        $branches = Branch::where('created_by', \Auth::user()->creatorId())->get();
        $totalHCs = [];
        $totalTargetHCs = [];

        foreach ($branches as $branch) {
            $headcounts = Employee::where('branch_id', $branch->id)->count();
            $totalHCs[] = $headcounts > 0 ? $headcounts : 0;

            $targetHeadcounts = Position::where('branch', $branch->id)->count();
            $totalTargetHCs[] = $targetHeadcounts > 0 ? $targetHeadcounts : 0;
        }

        $dataForBranchChart = [
            'branches' => $branches->pluck('name'),
            'totalHeadcounts' => $totalHCs,
            'totalTargetHeadcounts' => $totalTargetHCs,
        ];

        return $dataForBranchChart;
    }


    public function kpisTotalGrowth(Request $request)
    {
        // Get the month from the request parameters
        $month = $request->input('month');

        // Parse the month into Carbon instance
        $selectedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month);

        // Get total headcounts and total target headcounts for the selected month
        $totalHeadcounts = Employee::whereYear('company_doj', $selectedMonth->year)
            ->whereMonth('company_doj', $selectedMonth->month)
            ->count(); // Assuming joining_date is the column storing the employee joining date

        $totalTargetHeadcounts = Position::whereYear('created_at', $selectedMonth->year)
            ->whereMonth('created_at', $selectedMonth->month)
            ->count(); // Assuming created_at is the column storing the position creation date

        // Calculate total growth percentage for the selected month
        $totalGrowthPercentage = $totalTargetHeadcounts > 0 ? $totalHeadcounts / $totalTargetHeadcounts * 100 : 0;
        return response()->json(['totalHeadcounts' => $totalHeadcounts, 'totalTargetHeadcounts' => $totalTargetHeadcounts, 'total_growth_percentage' => $totalGrowthPercentage > 0 ? number_format($totalGrowthPercentage, 2) : 0]);
    }
}
