<?php

namespace Different\DifferentCore;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Different\LaravelBackpackDifferentExtension\Skeleton\SkeletonClass
 */
class DifferentCoreFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'different-core';
    }
}
