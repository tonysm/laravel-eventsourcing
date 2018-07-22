<?php

namespace App\Core\Entities;

trait UuidModel
{
    public static function findByUuidOrFail(string $uuid)
    {
        return static::where('uuid', $uuid)
            ->firstOrFail();
    }

    public static function findByUuid(string $uuid)
    {
        return static::where('uuid', $uuid)
            ->first();
    }
}
