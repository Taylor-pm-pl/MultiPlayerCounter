<?php

/**
 * MultiPlayerCounter plugin for PocketMine-MP
 * Copyright (C) 2022 DavidGlitch04 <https://github.com/DavidGlitch04>
 *
 * MultiPlayerCounter is licensed under the GNU General Public License v3.0 (GPL-3.0 License)
 *
 * GNU General Public License <https://www.gnu.org/licenses/>
 */

namespace davidglitch04\MultiPlayerCounter;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function intval;
use function serialize;
use function strval;
use function unserialize;
use function utf8_decode;
use function utf8_encode;

/**
 * Class UpdatePlayersTask
 * @package davidglitch04\MultiPlayerCounter
 */
class UpdatePlayersTask extends AsyncTask {

    private string $serversData;
	/**
	* @param array<int, object> $servers
	*/
	public function __construct(array $servers) {
    	$this->serversData = utf8_encode(serialize($servers));
	}

	public function onRun() : void {
		$res = ['count' => 0, 'maxPlayers' => 0, 'errors' => []];
		$serversConfig = (array) unserialize(utf8_decode($this->serversData));
		foreach ($serversConfig as $serverinfo){
			if ($serverinfo instanceof ServerInfo){
				$ip = $serverinfo->getIp();
				$port = $serverinfo->getPort();
				$status = $serverinfo->getInfo();
				if($status["Status"] == "online"){
					$res['count'] += $status["Players"];
					$res['maxPlayers'] += $status["Max"];
				} elseif ($status["Status"] == "offline"){
					$res['errors'][] = $status["error"];
				}
			}
		}
        $this->setResult($res);
    }

    public function onCompletion() : void {
		$server = Server::getInstance();
		/**@var array $res */
		$res = (array) $this->getResult();
		$err = (array) $res['errors'];
		foreach($err as $e){
			$server->getLogger()->warning(strval($e));
		}
		$plugin = $server->getPluginManager()->getPlugin("MultiPlayerCounter");
		if($plugin instanceof Main){
			$plugin->setCachedPlayers(intval($res['count']));
			$plugin->setCachedMaxPlayers(intval($res['maxPlayers']));
    	}
    }
}
