<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

class CustomerController extends Controller {
    public function create(){
        return view('customers.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name'=> 'required|string|max:255',
            'email'=> 'required|email|unique:customers,email',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            
        ], [
            'date_of_birth.before' => 'Must be at least 18 years old to register.',
            'date_of_birth.required' => "Please enter a date of birth",
            'name.required' => 'Please enter your name',
            'email.required' => "Please enter your email",
            'email.unique' => "This email is already being used",
        ]);
        Customers::create($validated);

        if ($request->ajax()) {
            return response()->json(['message' => 'Customer added successfully!']);
        }

        return redirect()->back()->with('success', 'Customer added successfully!');
    }

}