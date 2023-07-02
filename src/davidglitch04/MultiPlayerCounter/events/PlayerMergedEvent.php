<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\events;

use pocketmine\event\Event;

class PlayerMergedEvent extends Event {
	public function __construct(
		private int $countplayers
	) {
	}


	public function getPlayers() : int {
		return $this->countplayers;
	}
}