<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

class SortableTest extends TestCase
{
    public function testSetSortingOnRelationCreate()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        /** @var DefaultSortableItem $defaultItem */
        $defaultItem = $owner->defaultSortableItems()->create();
        /** @var CustomSortableItem $customItem */
        $customItem = $owner->customSortableItems()->create();

        $this->assertEquals(2, $defaultItem->getPosition());
        $this->assertEquals(2, $defaultItem[$defaultItem->getPositionColumnName()]);

        $this->assertEquals(2, $customItem->getPosition());
        $this->assertEquals(2, $customItem[$customItem->getPositionColumnName()]);
    }

    public function testSetSortingOnModelSave()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        $defaultItem = new DefaultSortableItem();
        $customItem = new CustomSortableItem();

        $owner->defaultSortableItems()->save($defaultItem);
        $owner->customSortableItems()->save($customItem);

        $this->assertEquals(2, $defaultItem->getPosition());
        $this->assertEquals(2, $defaultItem[$defaultItem->getPositionColumnName()]);

        $this->assertEquals(2, $customItem->getPosition());
        $this->assertEquals(2, $customItem[$customItem->getPositionColumnName()]);
    }

    public function testPredefinedSortingOnRelationCreate()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        $defaultItem = new DefaultSortableItem();
        $defaultItem->setPosition(3);

        $customItem = new CustomSortableItem();
        $customItem->setPosition(1);

        $owner->defaultSortableItems()->save($defaultItem);
        $owner->customSortableItems()->save($customItem);

        $this->assertEquals(3, $defaultItem->getPosition());
        $this->assertEquals(1, $customItem->getPosition());
    }

    public function testSetSortingOnAssociation()
    {
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        $defaultItem = new DefaultSortableItem();
        $defaultItem->owner()->associate($owner);
        $defaultItem->save();

        $customItem = new CustomSortableItem();
        $customItem->owner()->associate($owner);
        $customItem->save();

        $this->assertEquals(2, $defaultItem->getPosition());
        $this->assertEquals(2, $defaultItem[$defaultItem->getPositionColumnName()]);

        $this->assertEquals(2, $customItem->getPosition());
        $this->assertEquals(2, $customItem[$customItem->getPositionColumnName()]);
    }

    public function testDissociateResetPosition()
    {
        $owner = Owner::create();

        /** @var DefaultSortableItem $defaultItem */
        $defaultItem = DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        /** @var CustomSortableItem $customItem */
        $customItem = CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        $defaultItem->owner()->dissociate();
        $customItem->owner()->dissociate();

        $this->assertNull($defaultItem->getPosition());
        $this->assertNull($defaultItem[$defaultItem->getPositionColumnName()]);

        $this->assertNull($customItem->getPosition());
        $this->assertNull($customItem[$customItem->getPositionColumnName()]);
    }

    public function testSetSortingForDifferentContext()
    {
        /** @var Owner $owner1 */
        $owner1 = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner1->getKey(),
            'position' => 1,
        ]);

        /** @var Owner $owner2 */
        $owner2 = Owner::create();

        /** @var DefaultSortableItem $item */
        $item = $owner1->defaultSortableItems()->save(new DefaultSortableItem());
        $this->assertEquals(2, $item->getPosition());
        $this->assertEquals(2, $item[$item->getPositionColumnName()]);

        /** @var DefaultSortableItem $item */
        $item = $owner2->defaultSortableItems()->save(new DefaultSortableItem());
        $this->assertEquals(1, $item->getPosition());
        $this->assertEquals(1, $item[$item->getPositionColumnName()]);
    }

    public function testHasManyRelationIsSortedWhenFetching()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 3,
        ]);

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 2,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 2,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        $this->assertCount(3, $owner->defaultSortableItems()->get());
        $this->assertCount(2, $owner->customSortableItems()->get());

        $this->assertEquals([2, 3, 1], $owner->defaultSortableItems->pluck('id')->toArray());
        $this->assertEquals([2, 1], $owner->customSortableItems->pluck('id')->toArray());
    }

    public function testHasManySortedThroughRelationIsSortedWhenFetching()
    {
        /** @var Owner $owner */
        $owner = Owner::create();
        /** @var Middle $middle1 */
        $middle1 = $owner->middles()->create();
        /** @var Middle $middle1 */
        $middle2 = $owner->middles()->create();

        SortableItem::create([
            'middle_id' => $middle1->getKey(),
            'position' => 1,
        ]);

        SortableItem::create([
            'middle_id' => $middle1->getKey(),
            'position' => 2,
        ]);

        SortableItem::create([
            'middle_id' => $middle2->getKey(),
            'position' => 1,
        ]);

        $items = $owner->sortableItemsThroughMiddle()->get();

        $this->assertCount(3, $items);
        $this->assertEquals([1, 3, 2], $items->pluck('id')->toArray());
    }

    public function testHasManyThroughSortedRelationIsSortedWhenFetching()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        /** @var MiddleSortable $middle1 */
        $middle1 = MiddleSortable::create([
            'owner_id' => $owner->getKey(),
            'position' => 2
        ]);

        /** @var MiddleSortable $middle2 */
        $middle2 = MiddleSortable::create([
            'owner_id' => $owner->getKey(),
            'position' => 1
        ]);

        $items1 = $middle1->items()->createMany([
            [], []
        ])->pluck('id')->toArray();

        $items2 = $middle2->items()->createMany([
            [], [], []
        ])->pluck('id')->toArray();

        $items = $owner->items()->get();

        $this->assertCount(5, $items);
        $this->assertEquals(array_merge([], $items2, $items1), $items->pluck('id')->toArray());
    }

    public function testRelationsResort()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        $owner->defaultSortableItems()->createMany([
            [], [], [],
        ]);

        $owner->customSortableItems()->createMany([
            [], [],
        ]);

        $defaultItem = $owner->defaultSortableItems()->get()->offsetGet(1);
        $defaultItem->delete();

        $customItem = $owner->customSortableItems()->get()->offsetGet(0);
        $customItem->delete();

        $owner->defaultSortableItems()->resort();
        $owner->customSortableItems()->resort();

        $this->assertCount(2, $owner->defaultSortableItems);
        $this->assertEquals([1, 2], $owner->defaultSortableItems->pluck(DefaultSortableItem::getPositionColumnName())->toArray());
        $this->assertEquals([1, 3], $owner->defaultSortableItems->pluck('id')->toArray());

        $this->assertCount(1, $owner->customSortableItems);
        $this->assertEquals([1], $owner->customSortableItems->pluck(CustomSortableItem::getPositionColumnName())->toArray());
        $this->assertEquals([2], $owner->customSortableItems->pluck('id')->toArray());
    }

    public function testSetSortingForRelations()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 3,
        ]);

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 2,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 2,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        $defaultItems = $owner->defaultSortableItems()->get();
        $customItems = $owner->customSortableItems()->get();

        $defaultIds = $defaultItems->pluck('id')->shuffle();
        $owner->defaultSortableItems()->setSortingOrder($defaultIds);

        $customIds = $customItems->pluck('id')->shuffle();
        $owner->customSortableItems()->setSortingOrder($customIds);

        $this->assertEquals($defaultIds, $owner->defaultSortableItems->pluck('id'));
        $this->assertEquals($customIds, $owner->customSortableItems->pluck('id'));
    }

    public function testIgnoreSortingOnUpdate()
    {
        /** @var Owner $owner */
        $owner = Owner::create();

        DefaultSortableItem::create([
            'owner_id' => $owner->getKey(),
            'position' => 1,
        ]);

        CustomSortableItem::create([
            'owner_id' => $owner->getKey(),
            'place'    => 1,
        ]);

        /** @var DefaultSortableItem $defaultItem */
        $defaultItem = $owner->defaultSortableItems()->create();
        /** @var CustomSortableItem $customItem */
        $customItem = $owner->customSortableItems()->create();

        $this->assertEquals(2, $defaultItem->getPosition());
        $this->assertEquals(2, $defaultItem[$defaultItem->getPositionColumnName()]);

        $this->assertEquals(2, $customItem->getPosition());
        $this->assertEquals(2, $customItem[$customItem->getPositionColumnName()]);

        $defaultItem->touch();
        $customItem->touch();

        $this->assertEquals(2, $defaultItem->getPosition());
        $this->assertEquals(2, $defaultItem[$defaultItem->getPositionColumnName()]);

        $this->assertEquals(2, $customItem->getPosition());
        $this->assertEquals(2, $customItem[$customItem->getPositionColumnName()]);
    }
}
