<?php

namespace Different\DifferentCore\app\Models;

use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    protected $guarded = [];

    protected $dates = [
        'expires_at', 'consumed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        return ! $this->isExpired() && ! $this->isConsumed();
    }

    public function isExpired()
    {
        return $this->expires_at->isBefore(now());
    }

    public function isConsumed()
    {
        return $this->consumed_at !== null;
    }

    public function consume()
    {
        $this->consumed_at = now();
        $this->save();
    }
}
