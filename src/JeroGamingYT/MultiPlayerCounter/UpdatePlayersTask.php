<?php

/**
 * MultiPlayerCounter plugin for PocketMine-MP
 * Copyright (C) 2022 JeroGamingYT <https://github.com/JeroGamingYT>
 *
 * KeepInventory is licensed under the GNU General Public License v3.0 (GPL-3.0 License)
 *
 * GNU General Public License <https://www.gnu.org/licenses/>
 */
 
declare(strict_types=1);

namespace JeroGamingYT\MultiPlayerCounter;

use JeroGamingYT\MultiPlayerCounter\libs\libpmquery\PMQuery;
use JeroGamingYT\MultiPlayerCounter\libs\libpmquery\PmQueryException;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function explode;
use function json_decode;
use function json_encode;

class UpdatePlayersTask extends AsyncTask{

    /** @var string */
    private $serversData;

    public function __construct(array $serversConfig){
        $this->serversData = json_encode($serversConfig, JSON_THROW_ON_ERROR);
    }

    public function onRun() : void{
        $res = ['count' => 0, 'maxPlayers' => 0, 'errors' => []];
        $serversConfig = json_decode($this->serversData, true, 512, JSON_THROW_ON_ERROR);
        foreach($serversConfig as $serverConfigString){
            $serverData = explode(':', $serverConfigString);
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
        $res = $this->getResult();
		$server = Server::getInstance();
        foreach($res['errors'] as $e){
            $server->getLogger()->warning($e);
        }
        $plugin = $server->getPluginManager()->getPlugin('MultiPlayerCounter');
        if($plugin !== null && $plugin->isEnabled()){
            /** @var $plugin Main */
            $plugin->setCachedPlayers($res['count']);
            $plugin->setCachedMaxPlayers($res['maxPlayers']);
        }
    }

}
