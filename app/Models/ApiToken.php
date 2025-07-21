<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $fillable = ['name', 'token'];

    public static function generate(string $name): self
    {
        return self::create([
            'name' => $name,
            'token' => Str::random(64),
        ]);
    }

}
