<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\Event;

use pocketmine\event\Event;

class PlayerMergedEvent extends Event {
	/**
	 * @param int $countplayers
	 */
	public function __construct(private int $countplayers) {
		$this->countplayers = $countplayers;
	}


	public function getPlayers() : int {
		return $this->countplayers;
	}
}
