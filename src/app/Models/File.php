<?php

namespace Different\DifferentCore\app\Models;

use Carbon\Carbon;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

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
    use CrudTrait;

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
    protected $appends = [
        'url',
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
        if (!$this) {
            return "";
        }

        if(!config('different-core.config.unique_file_uuid_for_every_session_or_token', false)){
            return route('different-core.file', $this);
        }

        $token = request()->bearerToken() ?? session()?->getId();

        if(!$token)
        {
            return null;
        }

        $file_uuid = $this->file_uuids()->where('token', $token)->first();

        if(!$file_uuid)
        {
            return null;
        }

        return route('different-core.file', $file_uuid->uuid);
    }

    public function getThumbnailUrl()
    {
        if (!$this) {
            return "";
        }

        if(!config('different-core.config.unique_file_uuid_for_every_session_or_token', false)){
            return route('different-core.thumbnail', $this);
        }

        $token = request()->bearerToken() ?? session()?->getId();

        if(!$token)
        {
            return null;
        }

        $file_uuid = $this->file_uuids()->where('token', $token)->first();

        if(!$file_uuid)
        {
            return null;
        }

        return route('different-core.thumbnail', $file_uuid->uuid);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function file_uuids(): HasMany
    {
        return $this->hasMany(FileUuid::class);
    }

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
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getUrl(),
        );
    }

    protected function uuid(): Attribute
    {
        return Attribute::make(
            get: function(){
                if(!config('different-core.config.unique_file_uuid_for_every_session_or_token', false))
                {
                    return $this->attributes['uuid'];
                }
                $token = request()->bearerToken() ?? session()?->getId();

                if(!$token)
                {
                    return null;
                }

                $file_uuid = $this->file_uuids()->where('token', $token)->first();
                if(!$file_uuid)
                {
                    $file_uuid = FileUuid::query()
                        ->create([
                            'file_id' => $this->id,
                            'token' => $token,
                            'uuid' => Str::uuid()->toString(),
                        ]);
                }

                return $file_uuid->uuid;
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
