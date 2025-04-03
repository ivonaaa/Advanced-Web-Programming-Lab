<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'price',
        'tasks_completed', 'start_date', 'end_date'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teamMembers() {
        return $this->belongsToMany(User::class, 'project_user')->withTimestamps();
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function addMembers(array $users)
    {
        return $this->teamMembers()->attach($users);
    }

    public function removeMembers(array $users)
    {
        return $this->teamMembers()->detach($users);
    }

    public function addMember(User $user)
    {
        return $this->teamMembers()->attach($user);
    }

    public function removeMember(User $user)
    {
        return $this->teamMembers()->detach($user);
    }
}
