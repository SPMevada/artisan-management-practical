<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        "name",
        "email",
        "phone",
        "occupation",
        "student_id",
    ];

    public function student() {
        return $this->belongsTo(Student::class,'student_id');
    }
}
