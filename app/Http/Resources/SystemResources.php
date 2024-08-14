<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemResources extends JsonResource
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
        return tap(new SystemCollection($resource), function ($collection) {
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
            "id"                => $this->id,
            "application_name"  => $this->application_name,
            "title_name"        => $this->title_name,
            "email"             => $this->email,
            "phone"             => $this->phone,
            "city"              => $this->city,
            "postal_code"       => $this->postal_code,
            "address"           => $this->address,
            "country"           => $this->country,
            "currency"          => $this->currency,
            "currency_symbol"   => $this->currency_symbol,
            "state"             => $this->state,
            "app_version"       => $this->app_version,
            "date_format"       => $this->date_format,
            "time_zone"         => $this->time_zone,
            "logo"              => $this->logo,
            "favicon"           => $this->favicon,
            "is_active"         => $this->is_active,

        ]);
    }
}
