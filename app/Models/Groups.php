<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            $first_prefix = 'GF';
            $last_id = static::getLastID();
            $group->group_identifications = static::generateGroupId($first_prefix, $last_id);
        });
    }

    public static function generateGroupId($first_prefix, $last_id)
    {

        $group_identifi = "{$first_prefix}-{$last_id}";

        return $group_identifi;
    }

    public static function getLastID(){
        $lastGroupID = self::orderBy('group_identifications', 'desc')->first();

        if (!empty($lastGroupID)) {
            /* Extract - from group_identifications */
            $number = intval(substr($lastGroupID->group_identifications, strpos($lastGroupID->group_identifications, '-') + 1));
            /* increment by 1 */
            $nextNumber = $number + 1;
            $nextId = sprintf('%05d', $nextNumber);
        } else {
            $nextId = '00001';
        }

        return $nextId;
    }

    public function groups(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
