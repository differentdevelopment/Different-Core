<?php

namespace Different\DifferentCore\app\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

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
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public static function boot()
    {
        parent::boot();
    }
}
