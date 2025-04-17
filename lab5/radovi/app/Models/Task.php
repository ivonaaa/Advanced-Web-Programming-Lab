<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Obavezno dodaj fillable, jer inače ne možeš kreirati zadatke
    protected $fillable = [
        'user_id',
        'title_hr',
        'title_en',
        'description',
        'study_type',
        'accepted_student_id',
    ];

    // Veza prema nastavniku koji je kreirao rad
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Veza prema studentima koji su se prijavili na rad
    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    // Veza prema prihvaćenom studentu na radu
    public function acceptedStudent()
    {
        return $this->belongsTo(User::class, 'accepted_student_id');
    }
}
