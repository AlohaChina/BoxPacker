<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare(strict_types=1);

namespace DVDoug\BoxPacker\Test;

use DVDoug\BoxPacker\Box;
use DVDoug\BoxPacker\PackedItem;
use DVDoug\BoxPacker\PackedItemList;
use function count;
use DVDoug\BoxPacker\PositionallyConstrainedItem;
use function iterator_to_array;

class PositionallyConstrainedNoStackingTestItem extends TestItem implements PositionallyConstrainedItem
{
    /**
     * Hook for user implementation of item-specific constraints, e.g. max <x> batteries per box.
     *
     * @param  Box            $box
     * @param  PackedItemList $alreadyPackedItems
     * @param  int            $proposedX
     * @param  int            $proposedY
     * @param  int            $proposedZ
     * @param  int            $width
     * @param  int            $length
     * @param  int            $depth
     * @return bool
     */
    public function canBePacked(
        Box $box,
        PackedItemList $alreadyPackedItems,
        int $proposedX,
        int $proposedY,
        int $proposedZ,
        int $width,
        int $length,
        int $depth
    ): bool {
        $alreadyPackedType = array_filter(
            iterator_to_array($alreadyPackedItems, false),
            function (PackedItem $item) {
                return $item->getItem()->getDescription() === $this->getDescription();
            }
        );

        /** @var PackedItem $alreadyPacked */
        foreach ($alreadyPackedType as $alreadyPacked) {
            if (
                $alreadyPacked->getZ() + $alreadyPacked->getDepth() === $proposedZ &&
                $proposedX >= $alreadyPacked->getX() && $proposedX <= ($alreadyPacked->getX() + $alreadyPacked->getWidth()) &&
                $proposedY >= $alreadyPacked->getY() && $proposedY <= ($alreadyPacked->getY() + $alreadyPacked->getLength())) {
                return false;
            }
        }

        return true;
    }
}
