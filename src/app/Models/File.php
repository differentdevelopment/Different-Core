<?php

namespace Different\DifferentCore\app\Models;

use Carbon\Carbon;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class File
 *
 * @property int $id
 * @property string $uuid
 * @property int $partner_id
 * @property Partner $partner
 * @property string $original_name
 * @property string $mime_type
 * @property string $path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class File extends Model
{
    use Uuid;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'files';

    protected $fillable = [
        'uuid',
        'original_name',
        'mime_type',
        'path',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($file) {
            FilesController::deleteFile($file);
        });
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function getUrl()
    {
        return route('different-core.file', $this);
    }

    public function getThumbnailUrl()
    {
        return route('different-core.thumbnail', $this);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
