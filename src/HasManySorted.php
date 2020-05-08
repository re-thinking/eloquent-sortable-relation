<?php

namespace Rethinking\Eloquent\Relations\Sortable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasManySorted extends HasMany
{
    public function __construct(Builder $query, Model $parent, string $foreignKey, string $localKey)
    {
        parent::__construct($query, $parent, $foreignKey, $localKey);
        $this->query->orderBy($this->getRelated()->getPositionColumnName());
    }

    /**
     * @param iterable $ids
     * @param int      $start
     */
    public function setSortingOrder(iterable $ids, int $start = 1)
    {
        foreach ($ids as $id) {
            $query = clone $this->query;
            $query->where($this->getRelated()->getKeyName(), $id)
                ->update([$this->getRelated()->getPositionColumnName() => $start++]);
        }
    }

    /**
     * Resort existing records within related model.
     */
    public function resort(): void
    {
        $ids = $this->query->pluck($this->getRelated()->getKeyName());
        $this->setSortingOrder($ids);
    }
}
