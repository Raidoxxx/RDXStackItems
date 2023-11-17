<?php

namespace Raidoxx;

use pocketmine\entity\object\ItemEntity;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class StackTask extends Task
{

    public function onRun(): void
    {
        $players = Server::getInstance()->getOnlinePlayers();

        foreach ($players as $player) {
            $world = $player->getWorld();
            $chunkEntities = $world->getChunkEntities($player->getPosition()->getFloorX() >> 4, $player->getPosition()->getFloorZ() >> 4);

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


}