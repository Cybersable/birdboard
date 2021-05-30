<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'completed'
    ];

    protected $touches = [
        'project'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
