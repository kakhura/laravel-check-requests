<?php

namespace Kakhura\CheckRequest\Models\Request;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestIdentifier extends Model
{
    use SoftDeletes;

    protected $table = 'request_identifiers';

    protected $fillable = [
        'model_type',
        'model_id',
        'request_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}