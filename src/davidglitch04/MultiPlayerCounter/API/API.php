<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\API;

use davidglitch04\MultiPlayerCounter\ServerInfo;

interface API {

	public function getServerInfo(string $ip, int $port = 19132) : ServerInfo;


	public function isOnline(string $ip, int $port = 19132) : bool;
}
