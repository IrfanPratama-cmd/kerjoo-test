<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function show() {
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        return response()->json($employee);
    }

    public function update(Request $request){
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $employee->update($request->all());

        return response()->json($employee);
    }
}
