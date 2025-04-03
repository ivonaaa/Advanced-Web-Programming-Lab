<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'name', 'completed',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function setCompletedAttribute($value)
    {
        $this->attributes['completed'] = $value ? true : false;
    }

    public function getCompletedAttribute($value)
    {
        return (bool) $value;
    }
}
