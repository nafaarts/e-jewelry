<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Employee/Index', [
            'users' => User::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('user_code', 'like', "%{$search}%")
                        ->orWhere('indentity_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                })
                ->where('role', 'SALES')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employeeCode =  'U-' . str_pad(User::latest()->first()?->id + 1, 5, '0', STR_PAD_LEFT);
        return inertia('Employee/Create', [
            'employee_code' => $employeeCode
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_code' => 'required',
            'name' => 'required',
            'indentity_number' => 'nullable|numeric',
            'email' => 'required|email|unique:users,email|max:255',
            'phone_number' => 'required|numeric|min:8',
            'password' => 'required|confirmed|min:8',
            'address' => 'nullable',
            'photo' => 'nullable|image|max:5240',
            'role' => 'required',
            'is_active' => 'nullable',
            'remarks' => 'nullable'
        ]);

        if ($validated['photo']) {
            $request->file('photo')->store('public/user');
            $validated['photo'] = $request->file('photo')->hashName('user');
        }

        User::create($validated);

        return to_route('employees.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        return inertia('Employee/Edit', [
            'user' => $employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required',
            'indentity_number' => 'nullable|numeric',
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($employee->id)],
            'phone_number' => 'required|numeric|min:8',
            'password' => 'nullable|confirmed|min:8',
            'address' => 'nullable',
            'photo' => 'nullable|image|max:5240',
            'role' => 'required',
            'is_active' => 'nullable',
            'remarks' => 'nullable'
        ]);

        // dd($validated);

        if ($validated['photo']) {
            if ($employee->photo) unlink('storage/' . $employee->photo);
            $request->file('photo')->store('public/user');
            $validated['photo'] = $request->file('photo')->hashName('user');
        } else {
            $validated['photo'] = $employee->photo;
        }

        if (!$validated['password']) {
            $validated['password'] = $employee->password;
        }

        $employee->fill($validated);
        $employee->save();

        return to_route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        if ($employee->photo) unlink('storage/' . $employee->photo);

        $employee->delete();

        return to_route('employees.index');
    }
}