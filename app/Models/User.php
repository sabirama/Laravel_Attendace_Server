<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'uid'; // Set primary key
    public $incrementing = true; // Ensure auto-increment
    protected $keyType = 'int'; // Key type

    protected $fillable = [
        'uid',
        'name',
        'contact',
        'group'
    ];

    // Relationship with AttendanceLog
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'uid', 'uid');
    }
}
