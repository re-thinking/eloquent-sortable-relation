<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Illuminate\Database\Eloquent\Model;
use Rethinking\Eloquent\Relations\Sortable\HasSortedRelations;

class Middle extends Model
{
    use HasSortedRelations;

    protected $table = 'middle';

    public $timestamps = false;

    protected $guarded = [];

    public function items()
    {
        return $this->hasManySorted(SortableItem::class, 'middle_id');
    }
}
