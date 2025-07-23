<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Cart;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\ItemCollection;

class ItemCollectionTest extends TestCase
{
    public function testAddItem()
    {
        $collection = $this->createSUT();

        $this->assertCount(0, $collection);

        $item = ItemMother::random();
        
        $collection->add($item);
        
        $this->assertCount(1, $collection);
        $this->assertTrue($collection->contains($item));
    }

    public function testRemoveItem()
    {
        $collection = $this->createSUT();

        $item = ItemMother::random();
        $collection->add($item);
        
        $this->assertCount(1, $collection);
        $this->assertTrue($collection->contains($item));

        $collection->remove($item);
        
        $this->assertCount(0, $collection);
        $this->assertFalse($collection->contains($item));
    }

    public function testAddItemTwiceAddQuantities()
    {
        $collection = $this->createSUT();

        $item = ItemMother::random();
        $collection->add($item);
        
        $this->assertCount(1, $collection);
        $this->assertTrue($collection->contains($item));

        $collection->add($item);

        $findItem = $collection->findCombinable($item);
        
        $this->assertCount(1, $collection);
        $this->assertFalse($item->equals($findItem));
        $this->assertEquals($item->quantity() * 2, $findItem->quantity());
    }

    private function createSUT(): ItemCollection
    {
        return new ItemCollection();
    }
}