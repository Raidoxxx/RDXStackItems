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

            $itemGroups = []; // Array para armazenar grupos de itens

            foreach ($chunkEntities as $entity) {
                if ($entity instanceof ItemEntity) {
                    $item = $entity->getItem();
                    $itemHash = $item->getTypeId() . ":" . $item->getStateId(); // Identificador único do item

                    if (!isset($itemGroups[$itemHash])) {
                        // Se não existe um grupo para esse tipo de item, cria um novo
                        $itemGroups[$itemHash] = [];
                    }

                    $itemGroups[$itemHash][] = $entity;
                    $entity->setNameTag("§r§f" . $item->getName() . " §r§7x" . $item->getCount());
                    $entity->setNameTagAlwaysVisible(true);
                }
            }

            // Agrupamento de itens próximos feito, você pode manipular os grupos de itens aqui
            // Por exemplo, empilhar os itens dentro dos grupos, fundindo-os

            foreach ($itemGroups as $group) {
                if (count($group) > 1) {
                    $firstItem = array_shift($group); // Pegue o primeiro item do grupo

                    foreach ($group as $itemToMerge) {
                        $total = $firstItem->getItem()->getCount() + $itemToMerge->getItem()->getCount(); // Calcule a quantidade total de itens
                        $firstItem->getItem()->setCount($total);
                        // Junte os itens alterando a contagem do primeiro item
                        $itemToMerge->flagForDespawn(); // Marque os outros itens para serem removidos
                    }
                }
            }
        }
    }


}