<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\task;

use davidglitch04\MultiPlayerCounter\Main;
use davidglitch04\MultiPlayerCounter\ServerInfo;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;
use function intval;
use function strval;
class UpdatePlayersTask extends AsyncTask {
	private NonThreadSafeValue $serversData;

	/**
	 * @param array<int, object> $servers
	 */
	public function __construct(array $servers) {
		$this->serversData = new NonThreadSafeValue($servers);
	}


	public function onRun() : void {
		$res = ['count' => 0, 'maxPlayers' => 0, 'errors' => []];
		$serversConfig = $this->serversData->deserialize();
		/** @var array<ServerInfo> $serversConfig */
		foreach ($serversConfig as $serverInfo) {
			if ($serverInfo instanceof ServerInfo) {
				$status = $serverInfo->getInfo();
				if ($status["online"]) {
					$res['count'] += $status["players"];
					$res['maxPlayers'] += $status["max"];
				} else {
					$res['errors'][] = $status["error"];
				}
			}
		}
		$this->setResult($res);
	}


	public function onCompletion() : void {
		$server = Server::getInstance();
		$res = (array) $this->getResult();
		$err = (array) $res['errors'];
		foreach ($err as $e) {
			$server->getLogger()->warning(strval($e));
		}
		/** @var Main $plugin */
		$plugin = Main::getInstance();
		$plugin->setCachedPlayers(intval($res['count']));
		$plugin->setCachedMaxPlayers(intval($res['maxPlayers']));
	}
}
