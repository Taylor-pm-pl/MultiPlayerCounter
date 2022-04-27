<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use pocketmine\scheduler\Task;
use function strval;

/**
 * Class ScheduleUpdateTask
 * @package davidglitch04\MultiPlayerCounter
 */
class ScheduleUpdateTask extends Task {
	private Main $plugin;

    public function __construct(Main $plugin) {
    	$this->plugin = $plugin;
    }

    public function onRun() : void {
    	$servers = (array) $this->plugin->getConfig()->get('servers-to-query', []);
		$array = [];
		foreach ($servers as $info){
    		$array[] = new ServerInfo(strval($info));
        }
        $this->plugin->getServer()->getAsyncPool()->submitTask(new UpdatePlayersTask($array));
    }
}
