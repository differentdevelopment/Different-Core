<?php

namespace Different\DifferentCore\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Class Post
 *
 * @property int $id
 * @property string $title
 * @property string slug
 * @property string content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Posts extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['title', 'slug', 'content'];

}
