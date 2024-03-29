<?php

namespace Different\DifferentCore\app\Traits;

use Different\DifferentCore\app\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Different\DifferentCore\app\Models\Account;

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
        return $this->belongsTo(config('different-core.config.account_model_fqn', Account::class));
    }
}
