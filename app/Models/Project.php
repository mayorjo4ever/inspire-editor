<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function ($project) {
            $project->slug = $project->slug ?? Str::random(10);
        });
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function getFile(string $filename): ?ProjectFile
    {
        return $this->files()->where('filename', $filename)->first();
    }
}