<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\Item;

class ItemCollection implements \Countable
{
    public function __construct(private array $items = [])
    {
        
    }

    public function add(Item $item): void
    {
        $itemFound = $this->find($item);

        if (null !== $itemFound) {
            $item = $itemFound->combine($item);

            $this->remove($itemFound);            
        }
        
        $this->items[] = $item;
    }

    public function remove(Item $item): void
    {
        if (count($this->items) == 0) {
            return ;
        }

        foreach ($this->items as $i => $savedItem) {
            if ($item->equals($savedItem)) {
                unset($this->items[$i]);
            }
        }
    }

    public function contains(Item $item): bool
    {
        if (count($this->items) == 0) {
            return false;
        }

        foreach ($this->items as $internalItem) {
            if ($internalItem->equals($item)) {
                return true;
            }
        }

        return false;
    }

    public function find(Item $item): ?Item
    {
        if (count($this->items) == 0) {
            return null;
        }

        foreach ($this->items as $internalItem) {
            if ($internalItem->equals($item)) {
                return $internalItem;
            }
        }

        return null;
    }

    public function findCombinable(Item $item): ?Item
    {
        if (count($this->items) == 0) {
            return null;
        }

        foreach ($this->items as $internalItem) {
            if ($internalItem->combinable($item)) {
                return $internalItem;
            }
        }

        return null;
    }

    public function toArray(): array
    {
        return $this->items;
    }
    
    public function count(): int
    {
        return count($this->items);
    }
}