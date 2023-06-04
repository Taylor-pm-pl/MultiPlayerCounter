<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\API;

use davidglitch04\MultiPlayerCounter\ServerInfo;

class MPCAPI implements API {
	public static function getServerInfo(string $ip, int $port = 19132) : ServerInfo {
		return new ServerInfo($ip . ":" . $port);
	}


	public static function isOnline(string $ip, int $port = 19132) : bool {
		$api = new ServerInfo($ip . ":" . $port);
		$info = $api->getInfo();
		if ($info["Status"] == "online") {
			return true;
		}
		return false;
	}
}
