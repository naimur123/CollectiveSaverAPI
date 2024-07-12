<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResources extends JsonResource
{
    protected $withoutFields = [];

    /**
     * Set Hidden Item
     */
    public function hide(array $hide = []){
        $this->withoutFields = $hide;
        return $this;
    }

    /**
     * Filter Hide Items
     */
    protected function filterFields($data){
        return collect($data)->forget($this->withoutFields)->toArray();
    }

    /**
     * Collection
     */
    public static function collection($resource){
        return tap(new GroupCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->filter([
            "id"                    => $this->id,
            // "user_id"               => new UserResources($this->groups),
            "user_id"               => $this->user_id,
            "group_identifications" => $this->group_identifications,
            "name"                  => $this->name,
            "account_type"          => $this->account_type,
            "account_name"          => $this->account_name,
            "account_number"        => $this->account_number,
            "members"               => json_decode($this->members),
            "details"               => $this->details,
            "image"                 => $this->image,
            "is_active"             => $this->is_active,
            "created_at"            => $this->created_at,
            "updated_at"            => $this->updated_at
        ]);
    }
}
