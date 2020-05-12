<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rethinking\Eloquent\Relations\Sortable\HasSortingContext;
use Rethinking\Eloquent\Relations\Sortable\Sortable;

class SortableItem extends Model implements Sortable
{
    use HasSortingContext;

    protected $table = 'items_sortable';

    public $timestamps = false;

    protected $guarded = [];

    public function middle()
    {
        return $this->belongsTo(Middle::class, 'middle_id');
    }

    public function getSortingContext(): BelongsTo
    {
        return $this->middle();
    }
}