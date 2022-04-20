<?php

namespace davidglitch04\MultiPlayerCounter\command;

use CortexPE\Commando\BaseCommand;
use davidglitch04\MultiPlayerCounter\command\subcommands\AddServer;
use davidglitch04\MultiPlayerCounter\command\subcommands\RemoveServer;
use davidglitch04\MultiPlayerCounter\command\subcommands\UpdateTime;
use pocketmine\command\CommandSender;

/**
 * Class MPCCommand
 * @package davidglitch04\MultiPlayerCounter\command
 */
class MPCCommand extends BaseCommand{

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        //TODO:
    }
    
    public function prepare(): void
    {
        $this->setPermission("multiplayercount.allow.command");
        $this->registerSubCommand(new AddServer("add", "Add Server"));
        $this->registerSubCommand(new RemoveServer("remove", "Remove Server"));
        $this->registerSubCommand(new UpdateTime("time", "Update Time"));
    }
}