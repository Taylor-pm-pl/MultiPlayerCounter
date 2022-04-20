<?php

/**
 * MultiPlayerCounter plugin for PocketMine-MP
 * Copyright (C) 2022 DavidGlitch04 <https://github.com/DavidGlitch04>
 *
 * MultiPlayerCounter is licensed under the GNU General Public License v3.0 (GPL-3.0 License)
 *
 * GNU General Public License <https://www.gnu.org/licenses/>
 */
 
declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function array_values;
use function explode;
use function intval;
use function json_decode;
use function json_encode;
use function strval;

class UpdatePlayersTask extends AsyncTask{

    /** @var string */
    private string $serversData;
    public function __construct(array $serversConfig){/* @phpstan-ignore-line */
        $this->serversData = json_encode($serversConfig, JSON_THROW_ON_ERROR);
    }

    public function onRun() : void{
        $res = ['count' => 0, 'maxPlayers' => 0, 'errors' => []];
        $serversConfig = (array)json_decode($this->serversData, true, 512, JSON_THROW_ON_ERROR);
        foreach($serversConfig as $serverConfigString){
            $serverData = explode(':', strval($serverConfigString));
            $ip = $serverData[0];
            $port = (int) $serverData[1];
            try{
                $qData = PMQuery::query($ip, $port);
            }catch(PmQueryException $e){
                $res['errors'][] = 'Failed to query '.$serverConfigString.': '.$e->getMessage();
                continue;
            }
            $res['count'] += $qData['Players'];
            $res['maxPlayers'] += $qData['MaxPlayers'];
        }
        $this->setResult($res);
    }

    public function onCompletion() : void{
	/** @var array<string, int> $res */
        $res = $this->getResult();
	$server = Server::getInstance();
        foreach($res['errors'] as $e){
            $server->getLogger()->warning(strval($e));
        }
        $plugin = $server->getPluginManager()->getPlugin("MultiPlayerCounter");
        if($plugin instanceof Main){
            $plugin->setCachedPlayers(intval($res));
            $plugin->setCachedMaxPlayers(intval($res));
        }
    }
}
