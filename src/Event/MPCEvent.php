<?php

namespace davidglitch04\MultiPlayerCounter\Event;

use davidglitch04\MultiPlayerCounter\Main;
use pocketmine\event\Event;
use pocketmine\Server;

abstract class MPCEvent extends Event{

    protected Main $plugin;

    public function __construct()
    {
        $this->plugin = Server::getInstance()->getPluginManager()->getPlugin("MultiPlayerCounter");
    }

    public function getPlugin(): ?Main{
		return $this->plugin;
	}
}