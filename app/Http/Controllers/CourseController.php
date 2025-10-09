<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
    public function index()
    {
        return view('courses.index', [
            'courses' => Course::all()
        ]);
    }

    public function register(Request $request, $courseId)
    {
        $user = $request->user();
        $course = Course::findOrFail($courseId);

        $user->courses()->attach($course);
        return back()->with('success', 'Berhasil mendaftar mata kuliah!');
    }
}
