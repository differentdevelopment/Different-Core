<?php

namespace Different\DifferentCore\app\Models;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Different\DifferentCore\app\Traits\Uuids;

/**
 * Class File
 * @package Different\DifferentCore\app\Models
 * @property int $id
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
    use Uuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'files';
    protected $fillable = [
        'original_name',
        'mime_type',
        'path',
    ];

    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function identifiableAttribute(): string
    {
        return 'original_name';
    }

    /**
     * @param string|null $storage_path
     */
    public function orientate(): void
    {
        // TODO: Fixme, ez egy nagyon jó funkció, mi köze a File modellhez amúgy?
        /*$image_path = $this->getImagePath();
        ini_set('memory_limit', '256M');
        Image::make(Storage::url($image_path))
            ->orientate()
            ->save($image_path);*/
    }

    /**
     * A PHP a 8.0 verziótól képes lett kezelni a nevesített paramétereket, így onnantól lehet hívni csak attribútumra. Előtte sajnos meg kell majd adni a storage_pathot..
     * @param null|string $storage_path
     * @param array $attributes
     */
    public function resizeImage(array $attributes = []): void
    {
        // TODO: Fixme, ez egy nagyon jó funkció, mi köze a File modellhez amúgy?
        /*$attributes = array_merge($this->default_attributes, $attributes);
        $image_path = $this->getImagePath();
        ini_set('memory_limit', '256M');
        Image::make($image_path)
            ->resize($attributes['resize_x'], $attributes['resize_y'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($image_path);*/
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
