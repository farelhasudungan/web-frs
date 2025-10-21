<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Admin;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'student':
                $profile = $user->student;
                if (!$profile) {
                    return redirect()->route('profile.setup')
                           ->with('message', 'Please complete your student profile first.');
                }
                return view('profile.student', compact('user', 'profile'));
                
            case 'lecturer':
                $profile = $user->lecturer;
                if (!$profile) {
                    return redirect()->route('profile.setup')
                           ->with('message', 'Please complete your lecturer profile first.');
                }
                return view('profile.lecturer', compact('user', 'profile'));
                
            case 'admin':
                $profile = $user->admin;
                if (!$profile) {
                    return redirect()->route('profile.setup')
                           ->with('message', 'Please complete your admin profile first.');
                }
                return view('profile.admin', compact('user', 'profile'));
                
            default:
                abort(403, 'Invalid user role');
        }
    }
    
    public function setup()
    {
        $user = Auth::user();
        return view('profile.setup', compact('user'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'student':
                $validated = $request->validate([
                    'student_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'date_of_birth' => 'required|date',
                    'address' => 'required|string',
                    'admission_year' => 'required|integer|min:2000|max:' . (date('Y') + 1)
                ]);
                
                $validated['user_id'] = $user->id;
                $validated['email'] = $user->email;
                Student::create($validated);
                break;
                
            case 'lecturer':
                $validated = $request->validate([
                    'lecturer_name' => 'required|string|max:255',
                    'employee_id' => 'required|string|max:50|unique:lecturers',
                    'department' => 'required|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'specialization' => 'nullable|string'
                ]);
                
                $validated['user_id'] = $user->id;
                Lecturer::create($validated);
                break;
                
            case 'admin':
                $validated = $request->validate([
                    'admin_name' => 'required|string|max:255',
                    'department' => 'nullable|string|max:255'
                ]);
                
                $validated['user_id'] = $user->id;
                Admin::create($validated);
                break;
                
            default:
                abort(403, 'Invalid user role');
        }
        
        return redirect()->route('profile.show')
               ->with('success', 'Profile completed successfully!');
    }
    
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->roleProfile();
        
        if (!$profile) {
            return redirect()->route('profile.setup');
        }
        
        return view('profile.edit', compact('user', 'profile'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->roleProfile();
        
        if (!$profile) {
            return redirect()->route('profile.setup');
        }
        
        switch ($user->role) {
            case 'student':
                $validated = $request->validate([
                    'student_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'date_of_birth' => 'required|date',
                    'address' => 'required|string',
                    'admission_year' => 'required|integer|min:2000|max:' . (date('Y') + 1)
                ]);
                break;
                
            case 'lecturer':
                $validated = $request->validate([
                    'lecturer_name' => 'required|string|max:255',
                    'employee_id' => 'required|string|max:50|unique:lecturers,employee_id,' . $profile->id,
                    'department' => 'required|string|max:255',
                    'phone' => 'nullable|string|max:20',
                ]);
                break;
                
            case 'admin':
                $validated = $request->validate([
                    'admin_name' => 'required|string|max:255',
                    'department' => 'nullable|string|max:255'
                ]);
                break;
        }
        
        $profile->update($validated);
        
        return redirect()->route('profile.show')
               ->with('success', 'Profile updated successfully!');
    }
}
