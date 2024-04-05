<?php

namespace App\Http\Controllers;

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
            $positionsByStatus= $positions->groupBy('status')->map->count();

             // Group positions by job level and count the positions in each job level
             $positionsByJobLevel= $positions->groupBy('job_level')->map->count();

              // Group positions by branch and count the positions in each branch
            $positionsByBranch= $positions->groupBy('branches.name')->map->count();

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
}
