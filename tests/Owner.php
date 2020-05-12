<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Illuminate\Database\Eloquent\Model;
use Rethinking\Eloquent\Relations\Sortable\HasManySorted;
use Rethinking\Eloquent\Relations\Sortable\HasSortedRelations;

class Owner extends Model
{
    use HasSortedRelations;

    protected $table = 'owners';

    protected $guarded = [];

    public $timestamps = false;

    public function defaultSortableItems(): HasManySorted
    {
        return $this->hasManySorted(DefaultSortableItem::class, 'owner_id');
    }

    public function customSortableItems(): HasManySorted
    {
        return $this->hasManySorted(CustomSortableItem::class, 'owner_id');
    }

    public function middles()
    {
        return $this->hasMany(Middle::class, 'owner_id');
    }

    public function sortableMiddles()
    {
        $this->hasManySorted(MiddleSortable::class, 'owner_id');
    }

    public function sortableItemsThroughMiddle()
    {
        return $this->hasManySortedThrough(SortableItem::class, Middle::class, 'owner_id', 'middle_id');
    }

    public function items()
    {
        return $this->hasManyThroughSorted(Item::class, MiddleSortable::class, 'owner_id', 'middle_id');
    }
}
