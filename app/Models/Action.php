<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $hidden = [
        'content',
        'type',
        'id',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    var $labels = [
        'login' => 'Login to system',
        'logout' => 'Logout from system',
        'create_post' => 'Created Post',
        'update_post' => 'Updated Post',
        'delete_post' => 'Deleted Post',
        'update_profile' => 'Updated Profile',
        'update_contacts' => 'Updated Contacts',
        'update_password' => 'Updated Password',
    ];

    public function getLabel()
    {
        return !empty($this->labels[$this->type])? trans($this->labels[$this->type]): $this->type;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
