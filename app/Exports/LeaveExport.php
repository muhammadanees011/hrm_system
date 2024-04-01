<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user     = \Auth::user();
            $data= Leave::get();
            if (\Auth::user()->type == 'employee')
            {
                 $employee = Employee::where('user_id', '=', $user->id)->first();
                
                    $data= Leave::where('employee_id', '=', $employee->id)->get();
                    
                    foreach($data as $k=>$leave)
                    {    
                        
                        
                        $data[$k]["employee_id"]=Employee::employee_name($leave->employee_id);
                        $data[$k]["leave_type_id"]= !empty(\Auth::user()->getLeaveType($leave->leave_type_id))?\Auth::user()->getLeaveType($leave->leave_type_id)->title:'';
                        $data[$k]["created_by"]=Employee::login_user($leave->created_by);
                        $data[$k]['leave_duration'] = !empty($leave->leave_duration) ? ucfirst(str_replace('_', ' ', $leave->leave_duration)): '';
                        $data[$k]['start_time'] = !empty($leave->start_time) ? \Auth::user()->timeFormat($leave->start_time): '';
                        $data[$k]['end_time'] = !empty($leave->end_time) ? \Auth::user()->timeFormat($leave->end_time): '';
                        unset($leave->created_at,$leave->updated_at, $leave->manager_id, $leave->type);
                    }
                
            }
            else{  
                $data= Leave::get();
                foreach($data as $k=>$leave)
                {    
                    
                    
                    $data[$k]["employee_id"]=Employee::employee_name($leave->employee_id);
                    $data[$k]["leave_type_id"]= !empty(\Auth::user()->getLeaveType($leave->leave_type_id))?\Auth::user()->getLeaveType($leave->leave_type_id)->title:'';
                    $data[$k]["created_by"]=Employee::login_user($leave->created_by);
                    $data[$k]['leave_duration'] = !empty($leave->leave_duration) ? ucfirst(str_replace('_', ' ', $leave->leave_duration)): '';
                    $data[$k]['start_time'] = !empty($leave->start_time) ? \Auth::user()->timeFormat($leave->start_time): '';
                    $data[$k]['end_time'] = !empty($leave->end_time) ? \Auth::user()->timeFormat($leave->end_time): '';
                    unset($leave->created_at,$leave->updated_at, $leave->manager_id, $leave->type);
                }
                return $data;
                
            }
        
            return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "Employee Name",
            "Leave Type ",
            "Leave Duration",
            "Duration Hours",
            "Applied On",
            "Start Date",
            "End Date",
            "Start Time",
            "End Time",
            "Total Leaves Days",
            "Leave Reason",
            "Remark",
            "Status",
            "Created By"
        ];
    }
}
