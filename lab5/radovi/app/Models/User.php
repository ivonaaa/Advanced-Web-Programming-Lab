<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Dodano
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Provjera uloga
    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isTeacher() {
        return $this->role === 'nastavnik';
    }

    public function isStudent() {
        return $this->role === 'student';
    }

    // Zadaci na koje je student prijavljen
    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withPivot('priority'); // BONUS: dodajemo priority
    }

    // Zadaci koje je nastavnik kreirao
    public function teacherTasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }
}
