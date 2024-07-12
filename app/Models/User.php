<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $first_prefix = 'ACC';
            $last_id = static::getLastID();
            $user->user_id = static::generateStudentId($first_prefix, $last_id);
        });
    }

    public static function generateStudentId($first_prefix, $last_id)
    {

        $user_id = "{$first_prefix}-{$last_id}";

        return $user_id;
    }

    public static function getLastID(){
        $lastUser = self::orderBy('user_id', 'desc')->first();

        if (!empty($lastUser)) {
            /* Extract - from userid */
            $number = intval(substr($lastUser->user_id, strpos($lastUser->user_id, '-') + 1));
            /* increment by 1 */
            $nextNumber = $number + 1;
            $nextId = sprintf('%05d', $nextNumber);
        } else {
            $nextId = '00001';
        }

        return $nextId;
    }

    public function user_groups(){
        return $this->hasMany(Groups::class, 'user_id');
    }
}
