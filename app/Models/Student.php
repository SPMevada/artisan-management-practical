<?php

namespace App\Models;
 
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        "name",
        "email",
        "phone",
        "address",
        "class",
        "user_id"
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent() {
        return $this->hasMany(Parents::class, 'student_id');
    }
}
