<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'target',
        'email_notification',
        'created_by_id',
        'created_by_type'
    ];

    public function users() {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
