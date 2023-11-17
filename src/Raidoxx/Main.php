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
        $this->getScheduler()->scheduleRepeatingTask(new StackTask(), 20);
    }
}