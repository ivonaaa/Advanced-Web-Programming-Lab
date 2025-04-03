<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks;

        return view('tasks.index', compact('project', 'tasks'));
    }

    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $project->tasks()->create($validated);

        return redirect()->route('tasks.index', $project)->with('success', 'Zadatak uspješno dodan.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Project $project, Task $task)
    {
        if ($project->user_id !== auth()->id() && !$project->teamMembers->contains(auth()->user())) {
            return redirect()->route('projects.index')->with('error', 'Nemate dozvolu za uređivanje ovog zadatka.');
        }

        $task->completed = $request->has('completed');
        $task->save();

        return back()->with('success', 'Status zadatka ažuriran.');
    }

    public function updateCompletion(Request $request, Task $task)
    {
        $task->update([
            'completed' => $request->has('completed') ? true : false,
        ]);

        return back()->with('success', 'Status zadatka ažuriran.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Zadatak uspješno obrisan.');
    }
}
