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

use pocketmine\scheduler\Task;
use function strval;

/**
 * Class ScheduleUpdateTask
 * @package davidglitch04\MultiPlayerCounter
 */
class ScheduleUpdateTask extends Task {
 
    /** @var Main $plugin */
    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun() : void {
        $servers = (array)$this->plugin->getConfig()->get('servers-to-query', []);
        $array = [];
        foreach ($servers as $info){
            $array[] = new ServerInfo(strval($info));
        }
        $this->plugin->getServer()->getAsyncPool()->submitTask(new UpdatePlayersTask($array));
    }
}
