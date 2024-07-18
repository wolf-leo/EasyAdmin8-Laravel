<?php

namespace App\Casts;

use Carbon\CarbonInterface;
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
        if (!is_numeric($value)) return $value;
        return Carbon::createFromTimestamp($value, config('app.timezone'))->format(CarbonInterface::DEFAULT_TO_STRING_FORMAT);
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
