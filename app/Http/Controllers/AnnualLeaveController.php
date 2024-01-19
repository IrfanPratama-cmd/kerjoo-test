<?php

namespace App\Http\Controllers;

use App\Models\AnnualLeave;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

class AnnualLeaveController extends Controller
{
    public function getLeaveType(){
        $leaveType = LeaveType::all();

        return response()->json($leaveType);
    }

    public function postAnnualLeave(Request $request){
        $validator = Validator::make($request->all(), [
            'leave_type_id' => 'required',
            'description' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $employee = Employee::where('user_id', auth()->user()->id)->first();

        $data = AnnualLeave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => "menunggu",
        ]);

        return response()->json($data);

    }

    public function getAnnualLeave(){
        $data = QueryBuilder::for(AnnualLeave::class)
        ->allowedFilters(['employee.full_name', 'leavetype.name', 'id', 'start_date', 'end_date', 'status'])
        ->allowedSorts(['start_date', 'end_date']) // Kolom yang diizinkan untuk diurutkan
        ->defaultSort('-created_at') // Pengurutan default
        ->with(['employee', 'leave_type'])->paginate(10);

        return response()->json($data);
    }

    public function getAnnualLeaveID($id){
        $data = AnnualLeave::with(['employee', 'leave_type'])->where('id', $id)->first();

        return response()->json($data);
    }
}
