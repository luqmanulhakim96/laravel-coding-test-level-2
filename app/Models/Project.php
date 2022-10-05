<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'name',
        'product_owner_id'
    ];

    public function tasks()
    {
        return $this->hasMany('App\Models\Tasks');
    }

    public function product_owner()
    {
        return $this->belongsTo('App\Models\Users', 'product_owner_id');
    }
}
