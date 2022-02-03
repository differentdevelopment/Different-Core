<?php

namespace Different\DifferentCore\app\Models;

//use App\Notifications\CustomPasswordResetNotification;
//use App\Notifications\CustomRegistrationConfirmNotification;
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

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'partner_id',
        'timezone_id',
        'last_device',
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
    protected $casts = [];
    protected $guard_name = 'web';

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function verify()
    {
        $this->update([
            'email_verified_at' => Carbon::now()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function profile_image()
    {
        return $this->belongsTo(File::class);
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
    public function getProfileImage()
    {
        if ($this->profile_image_id) {
            return $this->profile_image;
        } else {
            return Gravatar::fallback('https://placehold.it/160x160/662d8c/b284d1/&text=' . strtoupper(substr($this->email, 0, 1)))
                ->get($this->email);
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        CauserResolver::setCauser(backpack_user());
        return LogOptions::defaults()
            ->logOnly(['name', 'email']);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
