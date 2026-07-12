<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'target_roles',
        'created_by',
    ];

    protected $casts = [
        'target_roles' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(Member::class, 'created_by');
    }
}
