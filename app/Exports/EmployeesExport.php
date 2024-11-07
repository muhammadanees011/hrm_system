<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
{
    $employees = Employee::with(['branch', 'department', 'designation', 'salaryType'])->get();
    $formattedData = $employees->map(function ($employee) {
        return [
            'name' => $employee->name, // Assuming these fields exist
            'date_of_birth' => $employee->date_of_birth,
            'gender' => $employee->gender,
            'phone_number' => $employee->phone_number,
            'address' => $employee->address,
            'email_id' => $employee->email,
            'password' => $employee->password, // Be cautious about exporting sensitive data
            'employee_id' => $employee->employee_id,
            'branch' => $employee->branch->name ?? '-',
            'department' => $employee->department->name ?? '-',
            'designation' => $employee->designation->name ?? '-',
            'date_of_join' => $employee->date_of_join,
            'account_holder_name' => $employee->account_holder_name,
            'account_number' => $employee->account_number,
            'bank_name' => $employee->bank_name,
            'bank_identifier_code' => $employee->bank_identifier_code,
            'branch_location' => $employee->branch_location,
            'salary_type' => $employee->salaryType->name ?? '-',
            'salary' => Employee::employee_salary($employee->salary),
            'created_by' => Employee::login_user($employee->created_by),
        ];
    });

    return $formattedData;
}


    public function headings(): array
    {
        return [
            "Name",
            "Date of Birth",
            "Gender",
            "Phone Number",
            "Address",
            "Email ID",
            "Password",
            "Employee ID",
            "Branch",
            "Department",
            "Designation",
            "Date of Join",
            "Account Holder Name",
            "Account Number",
            "Bank Name",
            "Bank Identifier Code",
            "Branch Location",
            "Salary Type",
            "Salary",
            "Created By"
        ];
    }
}
