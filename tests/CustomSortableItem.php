<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rethinking\Eloquent\Relations\Sortable\HasSortingContext;
use Rethinking\Eloquent\Relations\Sortable\Sortable;

class CustomSortableItem extends Model implements Sortable
{
    use HasSortingContext;

    protected $table = 'custom_items';

    protected $guarded = [];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function getSortingContext(): BelongsTo
    {
        return $this->owner();
    }

    public static function getPositionColumnName(): string
    {
        return 'place';
    }
}