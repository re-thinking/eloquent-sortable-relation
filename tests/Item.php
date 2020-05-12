<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public $timestamps = false;

    protected $guarded = [];
}
