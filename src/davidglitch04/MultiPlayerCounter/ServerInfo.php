<?php

declare(strict_types=1);

namespace davidglitch04\MultiPlayerCounter;

use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use function explode;
use function intval;
use function strval;

class ServerInfo {

	/**@var string $ip */
	protected string $ip;

	/**@var int $port */
	protected int $port;

    /**
     * @param string $data
     */
	public function __construct(string $data) {
		$info = explode(":", $data);
		$this->ip = strval($info[0]);
		$this->port = intval($info[1]);
	}

    /**
     * Get the IP address
     *
     * @return string
     */
	public function getIp() : string {
		return $this->ip;
	}

    /**
     * Get the port number
     *
     * @return int
     */
	public function getPort() : int {
		return $this->port;
	}

    /**
     * Convert IP and port number to string
     *
     * @return string
     */
	public function toString() : string {
		return $this->ip . ":" . $this->port;
	}

	/**
	 * @return array<string, int|string>
	 */
	public function getInfo() : array {
		try {
			$qData = PMQuery::query($this->getIp(), $this->getPort());

            return [
				"Status" => "online",
				"Players" => $qData['Players'],
				"Max" => $qData['MaxPlayers']
			];
		} catch (PmQueryException $e) {
			/**@var array $false */
			$false = [
				"Status" => "offline",
				"error" => "Failed to query " . $this->toString() . ": " . $e->getMessage()
			];
			return $false;
		}
	}
}
