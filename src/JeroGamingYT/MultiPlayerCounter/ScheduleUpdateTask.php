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

use pocketmine\scheduler\Task;
use pocketmine\Server;
class ScheduleUpdateTask extends Task{

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onRun() : void{
        $this->plugin->getServer()->getAsyncPool()->submitTask(new UpdatePlayersTask($this->plugin->getConfig()->get('servers-to-query')));
    }

}