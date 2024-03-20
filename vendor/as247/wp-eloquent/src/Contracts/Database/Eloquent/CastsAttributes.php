<?php

namespace As247\WpEloquent\Contracts\Database\Eloquent;

interface CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes);

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes);
}
