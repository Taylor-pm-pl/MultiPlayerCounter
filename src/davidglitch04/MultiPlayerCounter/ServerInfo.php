<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use function intval;

class ServerInfo {
	/**@var string $address */
	protected string $address;

	/**@var int $port */
	protected int $port;


	public function __construct(array $info) {
		$this->address = $info["address"];
		$this->port = intval($info["port"]);
	}

	/**
	 * Get the server address
	 */
	public function getAddress() : string {
		return $this->address;
	}

	/**
	 * Get the port number
	 */
	public function getPort() : int {
		return $this->port;
	}

	/**
	 * Convert to string
	 */
	public function __toString() : string {
		return $this->address . ":" . $this->port;
	}

	/**
	 * @return array<string, int|bool|string>
	 */
	public function getInfo() : array {
		try {
			$qData = PMQuery::query($this->getAddress(), $this->getPort());

			return [
				"online" => true,
				"players" => $qData['Players'],
				"max" => $qData['MaxPlayers']
			];
		} catch (PmQueryException $e) {
			return [
				"online" => false,
				"error" => "Failed to query " . $this->__toString() . ": " . $e->getMessage()
			];
		}
	}
}
