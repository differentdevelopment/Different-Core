<?php

namespace Different\DifferentCore\app\Models\Traits;

use Different\DifferentCore\app\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAccount
{
    protected static function booted()
    {
        static::addGlobalScope(new AccountScope());
    }

    public function scopeWithoutAccountScope($query)
    {
        return $query->withoutGlobalScope(AccountScope::class);
    }
    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
