<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter\events;

use pocketmine\event\Event;

class MaxSlotUpdateEvent extends Event {
	public function __construct(
        private int $slots
    ) {}


	public function getSlots() : int {
		return $this->slots;
	}
}