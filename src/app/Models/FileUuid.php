<?php

namespace Different\DifferentCore\app\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileUuid extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
