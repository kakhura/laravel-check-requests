<?php

namespace Kakhura\CheckRequest\Traits\Models;

trait ForDetail
{
    public function getTitleAttribute()
    {
        return $this->detail->first()->title;
    }

    public function getDescriptionAttribute()
    {
        return $this->detail->first()->description;
    }

    public function getAddressAttribute()
    {
        return $this->detail->first()->address;
    }
}
