<?php

/**
 * MultiPlayerCounter plugin for PocketMine-MP
 * Copyright (C) 2022 JeroGamingYT <https://github.com/JeroGamingYT>
 *
 * KeepInventory is licensed under the GNU General Public License v3.0 (GPL-3.0 License)
 *
 * GNU General Public License <https://www.gnu.org/licenses/>
 */
 
declare(strict_types=1);

namespace JeroGamingYT\MultiPlayerCounter;

use libpmquery\PMQuery;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\plugin\PluginBase;
use function class_exists;
use function count;

class Main extends PluginBase implements Listener{

    /** @var int */
    private $cachedPlayers;

    /** @var int */
    private $cachedMaxPlayers;

    public function onEnable() : void
	{
        if(!class_exists(PMQuery::class)){
            $this->getLogger()->error('Missing library cannot dynamically type plugin!');
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->cachedPlayers = 0;
        $this->cachedMaxPlayers = 0;

        $this->saveDefaultConfig();

        $this->getScheduler()->scheduleRepeatingTask(new ScheduleUpdateTask($this), $this->getConfig()->get('update-players-interval') * 20);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function getCachedPlayers() : int{
        return $this->cachedPlayers;
    }

    public function setCachedPlayers(int $cachedPlayers) : void{
        $this->cachedPlayers = $cachedPlayers;
    }

    public function getCachedMaxPlayers() : int{
        return $this->cachedMaxPlayers;
    }

    public function setCachedMaxPlayers(int $maxPlayers) : void{
        $this->cachedMaxPlayers = $maxPlayers;
    }

    public function queryRegenerate(QueryRegenerateEvent $event) : void{
        $event->getQueryInfo()->setPlayerCount($this->cachedPlayers + count($this->getServer()->getOnlinePlayers()));
        $event->getQueryInfo()->setMaxPlayerCount($this->cachedMaxPlayers + $this->getServer()->getMaxPlayers());
    }

}
