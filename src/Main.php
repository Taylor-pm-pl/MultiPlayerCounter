<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use davidglitch04\MultiPlayerCounter\API\MPCAPI;
use davidglitch04\MultiPlayerCounter\Event\MaxSlotUpdateEvent;
use davidglitch04\MultiPlayerCounter\Event\PlayerMergedEvent;
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
class Main extends PluginBase implements Listener {

    private int $cachedPlayers = 0;

    private int $cachedMaxPlayers = 0;

    public function onEnable() : void
	{
        if(!class_exists(PMQuery::class)){
            $this->getLogger()->error('Missing library libpmquery cannot dynamically type plugin!');
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->saveDefaultConfig();
        $this->getScheduler()->scheduleRepeatingTask(new ScheduleUpdateTask($this), $this->getConfig()->get('update-players-interval') * 20);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function getAPI() : MPCAPI{
        return new MPCAPI();
    }

    public function getCachedPlayers() : int {
        return $this->cachedPlayers;
    }

    public function setCachedPlayers(int $cachedPlayers) : void {
        $this->cachedPlayers = $cachedPlayers;
    }

    public function getCachedMaxPlayers() : int {
        return $this->cachedMaxPlayers;
    }

    public function setCachedMaxPlayers(int $maxPlayers) : void {
        $this->cachedMaxPlayers = $maxPlayers;
    }

    public function queryRegenerate(QueryRegenerateEvent $event) : void {
        $event->getQueryInfo()->setPlayerCount($this->cachedPlayers + count($this->getServer()->getOnlinePlayers()));
        (new PlayerMergedEvent($this->cachedPlayers))->call();
        $event->getQueryInfo()->setMaxPlayerCount($this->cachedMaxPlayers + $this->getServer()->getMaxPlayers());
        (new MaxSlotUpdateEvent($this->cachedMaxPlayers))->call();
    }
}
