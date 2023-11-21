<?php

namespace Raidoxx;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onSpawnItem(ItemSpawnEvent $event): void
    {
        $world = $event->getEntity()->getWorld();
        $chunkEntities = $world->getChunkEntities($event->getEntity()->getPosition()->getFloorX() >> 4, $event->getEntity()->getPosition()->getFloorZ() >> 4);

        $itemGroups = [];

        foreach ($chunkEntities as $entity) {
            if ($entity instanceof ItemEntity) {
                $item = $entity->getItem();
                $itemHash = $item->getTypeId() . ":" . $item->getStateId();

                if (!isset($itemGroups[$itemHash])) {
                    $itemGroups[$itemHash] = [];
                }

                $itemGroups[$itemHash][] = $entity;
                $entity->setNameTag("§r§f" . $item->getName() . " §r§7x" . $item->getCount());
                $entity->setNameTagAlwaysVisible(true);
            }
        }

        foreach ($itemGroups as $group) {
            if (count($group) > 1) {
                $firstItem = array_shift($group);

                foreach ($group as $itemToMerge) {
                    $total = $firstItem->getItem()->getCount() + $itemToMerge->getItem()->getCount();
                    $firstItem->getItem()->setCount($total);
                    $itemToMerge->flagForDespawn();
                }
            }
        }
    }
}