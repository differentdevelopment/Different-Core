<?php

namespace Different\DifferentCore\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Different\DifferentCore\app\Models\File;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Facades\CauserResolver;
use Different\DifferentCore\app\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Different\DifferentCore\app\Mail\MagicLoginLink;
use Illuminate\Support\Str;

/**
 * Class User
 * @package Different\DifferentCore\app\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property int $profile_image_id
 * @property File $profile_image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
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
    use SoftDeletes;

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

    public function getProfileImageUrl(): string
    {
        if ($this->profile_image_id && $this->profile_image) {
            return $this->profile_image?->getUrl();
        }
        return 'https://avatars.dicebear.com/api/initials/' . substr(Str::slug($this->name, ''), 0, 2) . '.svg';
    }

    public function sendLoginLink()
    {
        $plaintext = Str::random(32);
        $token = $this->loginTokens()->create([
            'token' => hash('sha256', $plaintext),
            'expires_at' => now()->addMinutes(5),
        ]);
        Mail::to($this->email)->queue(new MagicLoginLink($plaintext, $token->expires_at, $this->name));
    }
    #endregion

    #region Relációk
    /**
     * @return BelongsTo
     */
    public function profile_image(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loginTokens()
    {
        return $this->hasMany(LoginToken::class);
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
