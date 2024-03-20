<?php

namespace As247\WpEloquent\Database\Eloquent\Relations;

use Closure;
use As247\WpEloquent\Database\Eloquent\Builder;
use As247\WpEloquent\Database\Eloquent\Collection;
use As247\WpEloquent\Database\Eloquent\Model;
use As247\WpEloquent\Database\Query\Expression;
use As247\WpEloquent\Support\Arr;
use As247\WpEloquent\Support\Traits\ForwardsCalls;
use As247\WpEloquent\Support\Traits\Macroable;

/**
 * @mixin \As247\WpEloquent\Database\Eloquent\Builder
 */
abstract class Relation
{
    use ForwardsCalls, Macroable {
        __call as macroCall;
    }

    /**
     * The Eloquent query builder instance.
     *
     * @var \As247\WpEloquent\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * The parent model instance.
     *
     * @var \As247\WpEloquent\Database\Eloquent\Model
     */
    protected $parent;

    /**
     * The related model instance.
     *
     * @var \As247\WpEloquent\Database\Eloquent\Model
     */
    protected $related;

    /**
     * Indicates if the relation is adding constraints.
     *
     * @var bool
     */
    protected static $constraints = true;

    /**
     * An array to map class names to their morph names in database.
     *
     * @var array
     */
    public static $morphMap = [];

    /**
     * Create a new relation instance.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Builder  $query
     * @param  \As247\WpEloquent\Database\Eloquent\Model  $parent
     * @return void
     */
    public function __construct(Builder $query, Model $parent)
    {
        $this->query = $query;
        $this->parent = $parent;
        $this->related = $query->getModel();

        $this->addConstraints();
    }

    /**
     * Run a callback with constraints disabled on the relation.
     *
     * @param  \Closure  $callback
     * @return mixed
     */
    public static function noConstraints(Closure $callback)
    {
        $previous = static::$constraints;

        static::$constraints = false;

        // When resetting the relation where clause, we want to shift the first element
        // off of the bindings, leaving only the constraints that the developers put
        // as "extra" on the relationships, and not original relation constraints.
        try {
            return $callback();
        } finally {
            static::$constraints = $previous;
        }
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    abstract public function addConstraints();

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param  array  $models
     * @return void
     */
    abstract public function addEagerConstraints(array $models);

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array  $models
     * @param  string  $relation
     * @return array
     */
    abstract public function initRelation(array $models, $relation);

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array  $models
     * @param  \As247\WpEloquent\Database\Eloquent\Collection  $results
     * @param  string  $relation
     * @return array
     */
    abstract public function match(array $models, Collection $results, $relation);

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    abstract public function getResults();

    /**
     * Get the relationship for eager loading.
     *
     * @return \As247\WpEloquent\Database\Eloquent\Collection
     */
    public function getEager()
    {
        return $this->get();
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \As247\WpEloquent\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        return $this->query->get($columns);
    }

    /**
     * Touch all of the related models for the relationship.
     *
     * @return void
     */
    public function touch()
    {
        $model = $this->getRelated();

        if (! $model::isIgnoringTouch()) {
            $this->rawUpdate([
                $model->getUpdatedAtColumn() => $model->freshTimestampString(),
            ]);
        }
    }

    /**
     * Run a raw update against the base query.
     *
     * @param  array  $attributes
     * @return int
     */
    public function rawUpdate(array $attributes = [])
    {
        return $this->query->withoutGlobalScopes()->update($attributes);
    }

    /**
     * Add the constraints for a relationship count query.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Builder  $query
     * @param  \As247\WpEloquent\Database\Eloquent\Builder  $parentQuery
     * @return \As247\WpEloquent\Database\Eloquent\Builder
     */
    public function getRelationExistenceCountQuery(Builder $query, Builder $parentQuery)
    {
        return $this->getRelationExistenceQuery(
            $query, $parentQuery, new Expression('count(*)')
        )->setBindings([], 'select');
    }

    /**
     * Add the constraints for an internal relationship existence query.
     *
     * Essentially, these queries compare on column names like whereColumn.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Builder  $query
     * @param  \As247\WpEloquent\Database\Eloquent\Builder  $parentQuery
     * @param  array|mixed  $columns
     * @return \As247\WpEloquent\Database\Eloquent\Builder
     */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        return $query->select($columns)->whereColumn(
            $this->getQualifiedParentKeyName(), '=', $this->getExistenceCompareKey()
        );
    }

    /**
     * Get all of the primary keys for an array of models.
     *
     * @param  array  $models
     * @param  string|null  $key
     * @return array
     */
    protected function getKeys(array $models, $key = null)
    {
        return asdb_collect($models)->map(function ($value) use ($key) {
            return $key ? $value->getAttribute($key) : $value->getKey();
        })->values()->unique(null, true)->sort()->all();
    }

    /**
     * Get the underlying query for the relation.
     *
     * @return \As247\WpEloquent\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the base query builder driving the Eloquent builder.
     *
     * @return \As247\WpEloquent\Database\Query\Builder
     */
    public function getBaseQuery()
    {
        return $this->query->getQuery();
    }

    /**
     * Get the parent model of the relation.
     *
     * @return \As247\WpEloquent\Database\Eloquent\Model
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the fully qualified parent key name.
     *
     * @return string
     */
    public function getQualifiedParentKeyName()
    {
        return $this->parent->getQualifiedKeyName();
    }

    /**
     * Get the related model of the relation.
     *
     * @return \As247\WpEloquent\Database\Eloquent\Model
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return string
     */
    public function createdAt()
    {
        return $this->parent->getCreatedAtColumn();
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return string
     */
    public function updatedAt()
    {
        return $this->parent->getUpdatedAtColumn();
    }

    /**
     * Get the name of the related model's "updated at" column.
     *
     * @return string
     */
    public function relatedUpdatedAt()
    {
        return $this->related->getUpdatedAtColumn();
    }

    /**
     * Get the name of the "where in" method for eager loading.
     *
     * @param  \As247\WpEloquent\Database\Eloquent\Model  $model
     * @param  string  $key
     * @return string
     */
    protected function whereInMethod(Model $model, $key)
    {
        return $model->getKeyName() === asdb_last(explode('.', $key))
                    && in_array($model->getKeyType(), ['int', 'integer'])
                        ? 'whereIntegerInRaw'
                        : 'whereIn';
    }

    /**
     * Set or get the morph map for polymorphic relations.
     *
     * @param  array|null  $map
     * @param  bool  $merge
     * @return array
     */
    public static function morphMap(array $map = null, $merge = true)
    {
        $map = static::buildMorphMapFromModels($map);

        if (is_array($map)) {
            static::$morphMap = $merge && static::$morphMap
                            ? $map + static::$morphMap : $map;
        }

        return static::$morphMap;
    }

    /**
     * Builds a table-keyed array from model class names.
     *
     * @param  string[]|null  $models
     * @return array|null
     */
    protected static function buildMorphMapFromModels(array $models = null)
    {
        if (is_null($models) || Arr::isAssoc($models)) {
            return $models;
        }

        return array_combine(array_map(function ($model) {
            return (new $model)->getTable();
        }, $models), $models);
    }

    /**
     * Get the model associated with a custom polymorphic type.
     *
     * @param  string  $alias
     * @return string|null
     */
    public static function getMorphedModel($alias)
    {
        return static::$morphMap[$alias] ?? null;
    }

    /**
     * Handle dynamic method calls to the relationship.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        $result = $this->forwardCallTo($this->query, $method, $parameters);

        if ($result === $this->query) {
            return $this;
        }

        return $result;
    }

    /**
     * Force a clone of the underlying query builder when cloning.
     *
     * @return void
     */
    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
