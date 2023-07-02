<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\API;

use davidglitch04\MultiPlayerCounter\ServerInfo;

class ServerAPI {
	public static function getServerInfo(string $ip, int $port = 19132) : ServerInfo {
		return new ServerInfo([
			'address' => $ip,
			'port' => $port
		]);
	}


	public static function isOnline(string $ip, int $port = 19132) : bool {
		$info = self::getServerInfo($ip, $port)->getInfo();
		return $info["online"];
	}
}