<?php

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

    public function __construct(string $data)
    {
        $info = explode(":", $data);
        $this->ip = strval($info[0]);
        $this->port = intval($info[1]);
    }

    public function getIp(): string{
        return $this->ip;
    }

    public function getPort(): int{
        return $this->port;
    }

    public function toString(): string{
        return $this->ip.":".$this->port;
    }
	/**
     * @return array<string, int|string>
     */
	public function getInfo(): array{
		try {
		    $qData = PMQuery::query($this->getIp(), $this->getPort());
			/**@var array $array */
	    	    $array = [
		    	    "Status" => "online", 
		    	    "Players" => $qData['Players'],
		    	    "Max" => $qData['MaxPlayers']
	    	    ];
	    	    return $array;
        	}catch (PmQueryException $e){
		    /**@var array $false */
		    $false = [
			    "Status" => "offline", 
			    "error" => "Failed to query ".$this->toString().": ".$e->getMessage()
		    ];
            	    return $false;
        	}
	}
}
