<?php

namespace davidglitch04\MultiPlayerCounter;

class ServerInfo {

    protected string $ip;

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
}