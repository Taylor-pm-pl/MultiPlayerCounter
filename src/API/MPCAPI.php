<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\API;

use davidglitch04\MultiPlayerCounter\ServerInfo;

class MPCAPI implements API{

    public function getServerInfo(string $ip, int $port = 19132): ServerInfo
    {
        return new ServerInfo($ip.":".$port);
    }

    public function isOnline(string $ip, int $port = 19132): bool
    {
        $api = new ServerInfo($ip . ":" . $port);
        $info = $api->getInfo();
        if($info["Status"] == "online"){
            return true;
        } else{
            return false;
        }
    }

    public function getSoftware(string $ip, int $port = 19132): string
    {
        try {
            $status = json_decode(file_get_contents("https://api.mcsrvstat.us/2/" . $ip . ":" . $port));
        } catch (\Exception $e) {
            return "Error: Your IP does not open the port or the device does not match!";
        }
        try {
            return "SoftWare: " . $status->software;
        } catch (\Exception $e) {
            return "SoftWare: Error or has blocked queries!";
        }
    }
}