<?php

namespace As247\WpEloquent\Database\Eloquent\Factories;

use As247\WpEloquent\Database\Eloquent\Model;

class BelongsToManyRelationship
{
    /**
     * The related factory instance.
     *
     * @var \As247\WpEloquent\Database\Eloquent\Factories\Factory
     */
    protected $factory;

    /**
     * The pivot attributes / attribute resolver.
     *
     * @var callable|array
     */
    protected $pivot;

    /**
     * The relationship name.
     *
     * @var string
     */
    protected $relationship;

    /**
     * Create a new attached relationship definition.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Factories\Factory  $factory
     * @param  callable|array  $pivot
     * @param  string  $relationship
     * @return void
     */
    public function __construct(Factory $factory, $pivot, $relationship)
    {
        $this->factory = $factory;
        $this->pivot = $pivot;
        $this->relationship = $relationship;
    }

    /**
     * Create the attached relationship for the given model.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Model  $model
     * @return void
     */
    public function createFor(Model $model)
    {
        $this->factory->create([], $model)->each(function ($attachable) use ($model) {
            $model->{$this->relationship}()->attach(
                $attachable,
                is_callable($this->pivot) ? call_user_func($this->pivot, $model) : $this->pivot
            );
        });
    }
}
