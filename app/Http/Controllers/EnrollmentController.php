<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function availableCourses()
    {
        $user = Auth::user();
        $enrolledCourseIds = $user->student->courses()->pluck('courses.id');
        
        $courses = Course::with('prerequisites')
            ->where('status', 'active')
            ->whereNotIn('id', $enrolledCourseIds)
            ->get()
            ->map(function ($course) use ($user) {
                $enrollErrors = (array) ($user->student->canEnrollInCourse($course) ?? []);
                $course->can_enroll = empty($enrollErrors);
                $course->enrollment_errors = $enrollErrors;
                return $course;
            });

        return view('enrollments.available', compact('courses'));
    }

    public function myCourses()
    {
        $user = Auth::user();
        $enrolledCourses = $user->student->enrolledCourses()->get();
        $completedCourses = $user->student->completedCourses()->get();
        
        return view('enrollments.my-courses', compact('enrolledCourses', 'completedCourses'));
    }

    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();
        $errors = $user->student->canEnrollInCourse($course);

        if (!empty($errors)) {
            return redirect()->back()
                ->with('error', implode(', ', $errors));
        }

        DB::transaction(function () use ($user, $course) {
            Enrollment::create([
                'student_id' => $user->student->id,
                'course_id' => $course->id,
                'status' => 'enrolled'
            ]);

            $course->incrementEnrollment();
        });

        return redirect()->route('enrollments.my-courses')
            ->with('success', 'Successfully enrolled in ' . $course->name);
    }

    public function drop(Course $course)
    {
        $user = Auth::user();
        $enrollment = Enrollment::where('student_id', $user->student->id)
            ->where('course_id', $course->id)
            ->where('status', 'enrolled')
            ->first();

        if (!$enrollment) {
            return redirect()->back()
                ->with('error', 'You are not enrolled in this course');
        }

        DB::transaction(function () use ($enrollment, $course) {
            $enrollment->update(['status' => 'dropped']);
            $course->decrementEnrollment();
        });

        return redirect()->route('enrollments.my-courses')
            ->with('success', 'Successfully dropped ' . $course->name);
    }
}