<?php

namespace App\Models;

use App\Services\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'title',
        'description',
        'notes',
        'owner_id'
    ];

    protected static $recordableEvents = [
        'created',
        'updated'
    ];

    public function path()
    {
        return '/projects/' . $this->id;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'projects_users')->withTimestamps();
    }

}
