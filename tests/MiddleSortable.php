<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rethinking\Eloquent\Relations\Sortable\HasSortingContext;
use Rethinking\Eloquent\Relations\Sortable\Sortable;

class MiddleSortable extends Model implements Sortable
{
    use HasSortingContext;

    protected $table = 'middle_sortable';

    public $timestamps = false;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class, 'middle_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function getSortingContext(): BelongsTo
    {
        return $this->owner();
    }
}
