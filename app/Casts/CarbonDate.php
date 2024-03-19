<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CarbonDate implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     */
    public function get($model, $key, $value, $attributes): ?string
    {
        return Carbon::now(config('timezone'))->toDateTimeString();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
