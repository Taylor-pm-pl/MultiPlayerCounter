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
    /**
     * @var int $cachedPlayers
     */
	private int $cachedPlayers = 0;

    /**
     * @var int $cachedMaxPlayers
     */
	private int $cachedMaxPlayers = 0;

    /**
     * @return void
     */
	public function onEnable() : void {
		if (!class_exists(PMQuery::class)) {
			$this->getLogger()->error('Please download virion libpmquery at https://github.com/jasonwynn10/libpmquery!');
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$this->saveDefaultConfig();
		$this->getScheduler()->scheduleRepeatingTask(new ScheduleUpdateTask($this), $this->getConfig()->get('update-players-interval', 30) * 20);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if (VersionInfo::IS_DEVELOPMENT_BUILD) { /* @phpstan-ignore-line (If condition is always true.) */
			$this->getLogger()->warning("You are using the development builds. Development builds might have unexpected bugs, crash, break your plugins, corrupt all your data and more. Unless you're a developer and know what you're doing, please AVOID using development builds in production!");
		}
	}

    /**
     * Get API
     *
     * @return MPCAPI
     */
	public static function getAPI() : MPCAPI {
		return new MPCAPI();
	}

    /**
     * @return int
     */
	public function getCachedPlayers() : int {
		return $this->cachedPlayers;
	}

    /**
     * @param int $cachedPlayers
     *
     * @return void
     */
	public function setCachedPlayers(int $cachedPlayers) : void {
		$this->cachedPlayers = $cachedPlayers;
	}

    /**
     * @return int
     */
	public function getCachedMaxPlayers() : int {
		return $this->cachedMaxPlayers;
	}

    /**
     * @param int $maxPlayers
     *
     * @return void
     */
	public function setCachedMaxPlayers(int $maxPlayers) : void {
		$this->cachedMaxPlayers = $maxPlayers;
	}

    /**
     * @param QueryRegenerateEvent $event
     *
     * @return void
     */
	public function queryRegenerate(QueryRegenerateEvent $event) : void {
		$event->getQueryInfo()->setPlayerCount($this->cachedPlayers + count($this->getServer()->getOnlinePlayers()));
		(new PlayerMergedEvent($this->cachedPlayers))->call();
		$event->getQueryInfo()->setMaxPlayerCount($this->cachedMaxPlayers + $this->getServer()->getMaxPlayers());
		(new MaxSlotUpdateEvent($this->cachedMaxPlayers))->call();
	}
}
