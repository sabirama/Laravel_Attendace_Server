<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = ['uid'];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}
