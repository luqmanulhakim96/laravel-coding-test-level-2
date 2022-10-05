<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'title',
        'description',
        'status', // NOT_STARTED | IN_PROGRESS | READY_FOR_TEST |
        'project_id',
        'user_id',
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}
