<?php

namespace Different\DifferentCore\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Different\DifferentCore\app\Models\File;
use Different\DifferentCore\app\Models\TimeZone;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Facades\CauserResolver;
use Different\DifferentCore\app\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\Casts\Attribute;


/**
 * Class User
 * @package Different\DifferentCore\app\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property int $partner_id
 * @property Partner $partner
 * @property int $timezone_id
 * @property TimeZone $timezone
 * @property string $remember_token
 * @property int $profile_image_id
 * @property File $profile_image
 * @property string $last_device
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use CrudTrait;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use LogsActivity;
    use CausesActivity;
    use HasUploadFields;

    #region Globális változók
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image_id',
        'email_verified_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
    ];
    protected $guard_name = 'web';
    #endregion

    #region Funkciók
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            if ($user->profile_image_id) {
                $user->profile_image->delete();
            }
        });
    }

    public function verify()
    {
        $this->update([
            'email_verified_at' => Carbon::now()
        ]);
    }

    public function getProfileImageUrl()
    {
        if ($this->profile_image_id) {
            return $this->profile_image->getUrl();
        }
        return 'https://avatars.dicebear.com/api/initials/' . substr($this->name, 0, 2) . '.svg';
    }
    #endregion

    #region Relációk
    public function profile_image()
    {
        return $this->belongsTo(File::class);
    }
    #endregion

    #region Segítő (Accessor)
    public function getActivitylogOptions(): LogOptions
    {
        CauserResolver::setCauser(backpack_user());
        return LogOptions::defaults()->useLogName('users')->logOnly(['name', 'email']);
    }
    #endregion
}
