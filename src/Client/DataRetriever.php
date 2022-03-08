<?php

namespace App\Client;

use Psr\Log\LoggerInterface;

class DataRetriever
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function enviar(string $dato)
    {

        $this->logger->debug("LO QUE SEA!!!");
    }


}
