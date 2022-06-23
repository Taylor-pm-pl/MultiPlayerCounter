<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\Event;

use pocketmine\event\Event;

class MaxSlotUpdateEvent extends Event {
    /**
     * @param int $slots
     */
	public function __construct(private int $slots) {
		$this->slots = $slots;
	}

    /**
     * @return int
     */
	public function getSlots() : int {
		return $this->slots;
	}
}