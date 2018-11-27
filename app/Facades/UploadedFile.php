<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Libraries\UploadedFile as UploadedFileContract;

/**
 * @see \Illuminate\Filesystem\FilesystemManager
 */
class UploadedFile extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return UploadedFileContract::class;
    }

}
