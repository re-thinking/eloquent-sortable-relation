<?php

namespace Rethinking\Eloquent\Relations\Sortable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface Sortable
 * @package Rethinking\Eloquent\Relation\Sortable
 */
interface Sortable
{
    /**
     * @return string
     */
    public static function getPositionColumnName(): string;

    /**
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * @param int $value
     * @return void
     */
    public function setPosition(int $value): void;

    /**
     * @return BelongsTo
     */
    public function getSortingContext(): BelongsTo;
}
