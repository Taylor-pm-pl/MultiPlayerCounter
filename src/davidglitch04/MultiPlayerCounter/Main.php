<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use davidglitch04\MultiPlayerCounter\events\MaxSlotUpdateEvent;
use davidglitch04\MultiPlayerCounter\events\PlayerMergedEvent;
use davidglitch04\MultiPlayerCounter\task\ScheduleUpdateTask;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use function count;

class Main extends PluginBase implements Listener {
	use SingletonTrait;


	private int $cachedPlayers, $cachedMaxPlayers = 0;

	protected function onLoad() : void {
		self::setInstance($this);
	}

	public function onEnable() : void {
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getScheduler()->scheduleRepeatingTask(
			new ScheduleUpdateTask($this),
			$this->getConfig()->get('update-players-interval', 30) * 20
		);
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
