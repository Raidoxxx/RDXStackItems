<?php

namespace Raidoxx;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new StackTask(), 20);
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $player->getInventory()->addItem(VanillaItems::STONE_AXE());
        $player->dropItem(VanillaItems::STONE_AXE());$player->dropItem(VanillaItems::STONE_AXE());
    }



}