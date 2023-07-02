<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\task;

use davidglitch04\MultiPlayerCounter\Main;
use davidglitch04\MultiPlayerCounter\ServerInfo;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use function explode;

/**
 * Class ScheduleUpdateTask
 * @package davidglitch04\MultiPlayerCounter
 */
class ScheduleUpdateTask extends Task {
	public function onRun() : void {
		/** @var Main $plugin */
		$plugin = Main::getInstance();
		$servers = $plugin->getConfig()->get('servers-to-query', []);
		$array = [];
		foreach ($servers as $info) {
			$infoExplode = explode(":", $info);
			$array[] = new ServerInfo([
				'address' => strval($infoExplode[0]),
				'port' => strval($infoExplode[1]),
			]);
		}
		Server::getInstance()->getAsyncPool()->submitTask(new UpdatePlayersTask($array));
	}
}
