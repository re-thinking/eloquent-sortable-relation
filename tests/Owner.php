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
}