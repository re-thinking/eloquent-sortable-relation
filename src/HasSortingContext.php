<?php

namespace Rethinking\Eloquent\Relations\Sortable;

trait HasSortingContext
{
    public static function bootHasSortingContext()
    {
        static::creating(function (Sortable $model) {
            if (is_null($model->getPosition())) {
                $context = $model->getSortingContext();
                $foreignKey = $context->getForeignKeyName();

                if (isset($model->$foreignKey)) {
                    $max = $context->getParent()
                            ->newQueryWithoutScopes()
                            ->where($foreignKey, $model->$foreignKey)
                            ->max($model->getPositionColumnName()) + 1;

                    $model->setPosition($max);
                }
            }
        });
    }

    /**
     * @return string
     */
    public static function getPositionColumnName(): string
    {
        return 'position';
    }

    /**
     * @return int|null
     */
    final public function getPosition(): ?int
    {
        return $this->attributes[$this->getPositionColumnName()] ?? null;
    }

    /**
     * @param int $value
     */
    final public function setPosition(int $value): void
    {
        $this->attributes[$this->getPositionColumnName()] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function setRelation($relation, $value)
    {
        if ($this->getSortingContext()->getRelationName() === $relation) {
            if (is_null($value)) {
                $this->attributes[$this->getPositionColumnName()] = null;
            }
        }

        return parent::setRelation($relation, $value);
    }
}
