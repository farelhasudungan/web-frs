<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('prerequisites')->paginate(10);
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('courses.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:courses',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:6',
            'max_students' => 'required|integer|min:1',
            'prerequisites' => 'array',
            'prerequisites.*' => 'exists:courses,id'
        ]);

        DB::transaction(function () use ($validated) {
            $prerequisites = $validated['prerequisites'] ?? [];
            unset($validated['prerequisites']);
            
            $course = Course::create($validated);
            
            if (!empty($prerequisites)) {
                $course->prerequisites()->attach($prerequisites);
            }
        });

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully');
    }

    public function show(Course $course)
    {
        $course->load(['prerequisites', 'enrolledStudents']);
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $courses = Course::where('id', '!=', $course->id)->get();
        $selectedPrerequisites = $course->prerequisites->pluck('id')->toArray();
        return view('courses.edit', compact('course', 'courses', 'selectedPrerequisites'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => 'required|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:6',
            'max_students' => 'required|integer|min:1',
            'prerequisites' => 'array',
            'prerequisites.*' => 'exists:courses,id'
        ]);

        DB::transaction(function () use ($validated, $course) {
            $prerequisites = $validated['prerequisites'] ?? [];
            unset($validated['prerequisites']);
            
            $course->update($validated);
            $course->prerequisites()->sync($prerequisites);
        });

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully');
    }

    public function destroy(Course $course)
    {
        if ($course->enrollments()->exists()) {
            return redirect()->route('courses.index')
                ->with('error', 'Cannot delete course with enrolled students');
        }

        $course->delete();
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully');
    }
}