<?php

namespace Rethinking\Eloquent\Relations\Sortable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use InvalidArgumentException;

trait HasSortedRelations
{
    /**
     * @param $related
     * @param null $foreignKey
     * @param null $localKey
     *
     * @return HasManySorted
     */
    public function hasManySorted($related, $foreignKey = null, $localKey = null)
    {
        $this->checkClassIsSortable($related);
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKeyName();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasManySorted(
            $instance->newQuery(),
            $this,
            $instance->getTable().'.'.$foreignKey,
            $localKey
        );
    }

    /**
     * Define a has-many-sorted-through relationship.
     *
     * @param  string  $related
     * @param  string  $through
     * @param  string|null  $firstKey
     * @param  string|null  $secondKey
     * @param  string|null  $localKey
     * @param  string|null  $secondLocalKey
     * @return HasManyThrough
     */
    public function hasManySortedThrough($related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null)
    {
        $this->checkClassIsSortable($related);
        $instance = $this->newRelatedInstance($related);

        $through = new $through;

        $firstKey = $firstKey ?: $this->getForeignKey();

        $secondKey = $secondKey ?: $through->getForeignKey();

        return $this->newHasManySortedThrough(
            $instance->newQuery(),
            $this,
            $through,
            $firstKey,
            $secondKey,
            $localKey ?: $this->getKeyName(),
            $secondLocalKey ?: $through->getKeyName()
        );
    }

    /**
     * Define a has-many-through-sorted relationship.
     *
     * @param  string  $related
     * @param  string  $through
     * @param  string|null  $firstKey
     * @param  string|null  $secondKey
     * @param  string|null  $localKey
     * @param  string|null  $secondLocalKey
     * @return HasManyThrough
     */
    public function hasManyThroughSorted($related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null)
    {
        $this->checkClassIsSortable($through);
        $instance = $this->newRelatedInstance($related);

        $through = new $through;

        $firstKey = $firstKey ?: $this->getForeignKey();

        $secondKey = $secondKey ?: $through->getForeignKey();

        return $this->newHasManyThroughSorted(
            $instance->newQuery(),
            $this,
            $through,
            $firstKey,
            $secondKey,
            $localKey ?: $this->getKeyName(),
            $secondLocalKey ?: $through->getKeyName()
        );
    }

    /**
     * Instantiate a new HasManySorted relationship.
     *
     * @param Builder $query
     * @param Model   $parent
     * @param string  $foreignKey
     * @param string  $localKey
     *
     * @return HasManySorted
     */
    protected function newHasManySorted(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new HasManySorted($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Instantiate a new HasManyThrough relationship.
     *
     * @param  Builder $query
     * @param  Model   $farParent
     * @param  Model   $throughParent
     * @param  string  $firstKey
     * @param  string  $secondKey
     * @param  string  $localKey
     * @param  string  $secondLocalKey
     * @return HasManyThrough
     */
    protected function newHasManySortedThrough(Builder $query, Model $farParent, Model $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey)
    {
        return new HasManySortedThrough($query, $farParent, $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey);
    }

    /**
     * Instantiate a new HasManyThrough relationship.
     *
     * @param  Builder $query
     * @param  Model   $farParent
     * @param  Model   $throughParent
     * @param  string  $firstKey
     * @param  string  $secondKey
     * @param  string  $localKey
     * @param  string  $secondLocalKey
     * @return HasManyThrough
     */
    protected function newHasManyThroughSorted(Builder $query, Model $farParent, Model $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey)
    {
        return new HasManyThroughSorted($query, $farParent, $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey);
    }

    private function checkClassIsSortable(string $class)
    {
         if (!in_array(Sortable::class, class_implements($class))) {
             throw new InvalidArgumentException("$class should implement Sortable interface");
         }
    }
}
