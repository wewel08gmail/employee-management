<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('dashboard', compact('employees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'Manager') {
            $request->validate([
                'name' => ['required'],
                'position' => ['required'],
            ]);

            Employee::create([
                'name' => $request->name,
                'position' => $request->position,
            ]);

            return redirect()->route('dashboard');
        }

        if ($user->role === 'Web Developer' && $request->position === 'Web Developer') {
            $request->validate([
                'name' => ['required'],
                'position' => ['required'],
            ]);

            Employee::create([
                'name' => $request->name,
                'position' => $request->position,
            ]);

            return redirect()->route('dashboard');
        }

        if ($user->role === 'Web Designer' && $request->position === 'Web Designer') {
            $request->validate([
                'name' => ['required'],
                'position' => ['required'],
            ]);

            Employee::create([
                'name' => $request->name,
                'position' => $request->position,
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['position' => 'Unauthorized action']);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employee = Employee::find($id);

        if (!$employee) {
            return back()->withErrors(['employee' => 'Employee not found']);
        }

        if ($user->role === 'Manager') {
            $request->validate([
                'name' => ['required'],
                'position' => ['required'],
            ]);

            $employee->update([
                'name' => $request->name,
                'position' => $request->position,
            ]);

            //return redirect()->route('dashboard');            
            return response()->json($employee);
        }

        if ($user->role === 'Web Developer' && $employee->position === 'Web Developer') {
            $request->validate([
                'name' => ['required'],                
            ]);

            $employee->update([
                'name' => $request->name,                
            ]);

            //return redirect()->route('dashboard');
            return response()->json($employee);
        }

        if ($user->role === 'Web Designer' && $employee->position === 'Web Designer') {
            $request->validate([
                'name' => ['required'],                              
            ]);

            $employee->update([
                'name' => $request->name,                
            ]);

            //return redirect()->route('dashboard');
            return response()->json($employee);
        }

        //return back()->withErrors(['position' => 'Unauthorized action']);        
        return response()->json(['message' => 'Employee updated error!']);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $employee = Employee::find($id);

        if (!$employee) {
            return back()->withErrors(['employee' => 'Employee not found']);
        }

        if ($user->role === 'Manager' || ($user->role === 'Web Developer' && $employee->position === 'Web Developer') || ($user->role === 'Web Designer' && $employee->position === 'Web Designer')) {
            $employee->delete();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['position' => 'Unauthorized action']);
    }
    
    public function show($id)
    {
        $employee = Employee::find($id);
       
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }
}
