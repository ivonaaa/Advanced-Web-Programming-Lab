<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isTeacher()) {
            $myTasks = Task::where('user_id', $user->id)->with('students', 'acceptedStudent')->get();
            return view('tasks.index', compact('myTasks'));
        }

        if ($user->isStudent()) {
            $tasks = Task::with('teacher')->get();
            return view('tasks.index', compact('tasks'));
        }

        // Admin ili svi ostali
        $tasks = Task::with('teacher')->get();
        return view('tasks.index', compact('tasks'));
    }


    public function create()
    {
        abort_unless(Auth::user()->isTeacher(), 403);
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isTeacher(), 403);

        $request->validate([
            'title_hr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'study_type' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title_hr' => $request->title_hr,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'study_type' => $request->study_type,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Rad dodan!');
    }

    public function apply(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*' => 'exists:tasks,id',
        ]);

        if (count($request->tasks) > 5) {
            return back()->withErrors('Možete prijaviti najviše 5 radova.');
        }

        auth()->user()->tasks()->sync($request->tasks);

        return redirect()->route('tasks.index')->with('success', 'Prijava uspješna!');
    }

    public function acceptStudent(Request $request, Task $task)
    {
        abort_unless(Auth::user()->id === $task->user_id, 403);

        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $task->update(['accepted_student_id' => $request->student_id]);

        return back()->with('success', 'Student prihvaćen!');
    }
}
