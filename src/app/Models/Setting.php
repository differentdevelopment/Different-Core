<?php

namespace Different\DifferentCore\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CrudTrait;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'label',
        'value',
        'type',
        'tab',
        'name',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public static function boot()
    {
        parent::boot();
    }
}
