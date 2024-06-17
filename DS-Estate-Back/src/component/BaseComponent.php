<?php

namespace src\component;

use src\utils\CustomLogger;

abstract class BaseComponent
{
    public CustomLogger $logger;

    public function __construct()
    {
        $this->logger = new CustomLogger();
    }

}
