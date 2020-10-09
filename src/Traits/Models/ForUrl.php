<?php

namespace Kakhura\CheckRequest\Traits\Models;

use Illuminate\Support\Str;

trait ForUrl
{
    public function getUrlAttribute()
    {
        return url(sprintf('%s/%s-%s', $this->urlSegment, $this->id, Str::slug($this->title))) ;
    }
}
