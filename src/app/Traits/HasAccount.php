<?php

namespace Different\DifferentCore\app\Traits;

use Different\DifferentCore\app\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAccount
{
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
