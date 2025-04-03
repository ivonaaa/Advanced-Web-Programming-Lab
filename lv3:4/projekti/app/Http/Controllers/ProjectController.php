<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ownedProjects = $user->projects;
        $participatingProjects = $user->projectsParticipating;

        return view('projects.index', compact('ownedProjects', 'participatingProjects'));
    }

    public function create()
    {
        $users = User::all(); 
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'team_members' => 'array', 
            'tasks' => 'array', 
        ]);

        $project = auth()->user()->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($request->has('team_members')) {
            $project->teamMembers()->attach($request->team_members);
        }

        if ($request->has('tasks')) {
            foreach ($request->tasks as $taskName) {
                $project->tasks()->create(['name' => $taskName]);
            }
        }

        return redirect()->route('projects.index')->with('success', 'Projekt uspješno kreiran.');
    }

    public function edit(Project $project)
    {
        $users = User::all(); // Get all users
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'team_members' => 'nullable|array',
            'tasks' => 'nullable|array',
        ]);

        $project->update($data);

        if (isset($data['team_members'])) {
            $project->teamMembers()->sync($data['team_members']);
        }

        if (isset($data['tasks'])) {
            $existingTaskIds = $project->tasks->pluck('id')->toArray(); 
            $taskDataIds = [];

            foreach ($data['tasks'] as $taskData) {
                if (isset($taskData['id'])) {
                    $task = Task::find($taskData['id']);
                    if ($task) {
                        $task->update([
                            'name' => $taskData['name'],
                            'completed' => isset($taskData['completed']) ? true : false,
                        ]);
                        $taskDataIds[] = $task->id; 
                    }
                } else {
                    $task = $project->tasks()->create([
                        'name' => $taskData['name'],
                        'completed' => isset($taskData['completed']) ? true : false,
                    ]);
                    $taskDataIds[] = $task->id; 
                }
            }

            $tasksToDelete = array_diff($existingTaskIds, $taskDataIds);
            if ($tasksToDelete) {
                Task::whereIn('id', $tasksToDelete)->delete();
            }
        }

        return redirect()->route('projects.index')->with('success', 'Projekt ažuriran');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->tasks()->delete();
        $project->teamMembers()->detach();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projekt uspješno obrisan.');
    }
}
