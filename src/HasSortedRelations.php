<?php

namespace Rethinking\Eloquent\Relations\Sortable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasSortedRelations
{
    /**
     * @param $related
     * @param null $foreignKey
     * @param null $localKey
     * @return HasManySorted
     */
    public function hasManySorted($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);
        if (!$instance instanceof Sortable) {
            throw new \InvalidArgumentException("$related model should implement Sortable interface");
        }

        $foreignKey = $foreignKey ?: $this->getForeignKeyName();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasManySorted(
            $instance->newQuery(),
            $this,
            $instance->getTable() . '.' . $foreignKey,
            $localKey
        );
    }

    /**
     * Instantiate a new HasManySorted relationship.
     *
     * @param  Builder  $query
     * @param  Model  $parent
     * @param  string $foreignKey
     * @param  string $localKey
     * @return HasManySorted
     */
    protected function newHasManySorted(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new HasManySorted($query, $parent, $foreignKey, $localKey);
    }
}
