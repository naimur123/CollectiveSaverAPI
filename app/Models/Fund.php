<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    public function groups(){
        return $this->belongsTo(Groups::class, 'group_id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
