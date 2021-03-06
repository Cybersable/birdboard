<?php

namespace App\Models;

use App\Services\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, RecordsActivity;

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

    public function complete()
    {
        $this->update(['completed' => true]);
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
    }

}
