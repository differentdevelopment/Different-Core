<?php

namespace Different\DifferentCore\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Models\Role as OriginalRole;

/**
 * Class Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property string $readable_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Role extends OriginalRole
{
    use CrudTrait;

    protected $fillable = [
        'name',
        'guard_name',
        'readable_name',
    ];

    public $timestamps = true;
}
