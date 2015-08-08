<?php

namespace TwitterConsole\Console;

use Symfony\Component\Console\Application as BaseApplication;
use TwitterConsole\Console\Command\StreamCommand;

class Application extends BaseApplication
{
    const NAME = 'Twitter Console Application';
    const VERSION = '0.0';

    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);
        $this->add(new StreamCommand());
    }
}
