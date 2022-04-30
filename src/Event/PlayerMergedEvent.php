<?php

namespace davidglitch04\MultiPlayerCounter\Event;

class PlayerMergedEvent extends MPCEvent{

    public function __construct(private int $countplayers)
    {
        $this->countplayers = $countplayers;
    }

    public function getPlayers() : int{
		return $this->countplayers;
	}
}