<?php

namespace src\utils;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomLogger
{

    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('ds_logger');
        $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

    }

    public function debug(string $message): void
    {
        $this->logger->debug($message);
    }

    public function info(string $message): void
    {
        $this->logger->info($message);
    }

    public function warning(string $message): void
    {
        $this->logger->warning($message);
    }

    public function error(string $message): void
    {
        $this->logger->error($message);
    }

    public function critical(string $message): void
    {
        $this->logger->critical($message);
    }

}
