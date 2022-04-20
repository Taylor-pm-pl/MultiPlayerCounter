<?php

/**
 * MultiPlayerCounter plugin for PocketMine-MP
 * Copyright (C) 2022 DavidGlitch04 <https://github.com/DavidGlitch04>
 *
 * MultiPlayerCounter is licensed under the GNU General Public License v3.0 (GPL-3.0 License)
 *
 * GNU General Public License <https://www.gnu.org/licenses/>
 */
 
declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use libpmquery\PMQuery;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\plugin\PluginBase;
use function class_exists;
use function count;

/**
 * Class Main
 * @package davidglitch04\MultiPlayerCounter
 */
class Main extends PluginBase implements Listener{

    /** @var int */
    private int $cachedPlayers = 0;

    /** @var int */
    private int $cachedMaxPlayers = 0;

    public function onEnable() : void
	{
        if(!class_exists(PMQuery::class)){
            $this->getLogger()->error('Missing library cannot dynamically type plugin!');
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
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
