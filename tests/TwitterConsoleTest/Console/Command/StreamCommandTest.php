<?php

namespace TwitterConsoleTest\Console\Command;

use Symfony\Component\Console\Tester\CommandTester;
use TwitterConsole\Console\Command\StreamCommand;
use Symfony\Component\Console\Application;

class StreamCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new StreamCommand());

        $command = $application->find('twitter:stream');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'tag'    => '#coderockr'
            )
        );

        $this->assertContains('#coderockr', $commandTester->getDisplay());
    }
}
