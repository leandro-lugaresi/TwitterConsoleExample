<?php

namespace TwitterConsole\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OldSound\RabbitMqBundle\DependencyInjection\OldSoundRabbitMqExtension;
use OldSound\RabbitMqBundle\DependencyInjection\Compiler\RegisterPartsPass;
use TwitterConsole\Console\Command\StreamCommand;

class Application extends BaseApplication
{

    public function __construct()
    {
        // create and populate the container
        $this->container = new ContainerBuilder();
        $extension = new OldSoundRabbitMqExtension();
        $this->container->registerExtension($extension);
        $this->container->addCompilerPass(new RegisterPartsPass());
        // some useful paths
        $paths = array();
        $paths['root'] = __DIR__ . '/../../../';
        $paths['config'] = $paths['root'] . 'app/config/';
        $this->container->setParameter('paths', $paths);
        // the main config
        $loader = new YamlFileLoader($this->container, new FileLocator($paths['config']));
        $loader->load('config.yml');
        //Compiler
        $this->container->loadFromExtension($extension->getAlias());
        $this->container->compile();

        // construct the application
        $app = $this->container->getParameter('application');
        parent::__construct($app['name'], $app['version']);
        $this->container->get('old_sound_rabbit_mq.connection.default');
        // and add commands to it
        $this->addConsoleCommands();
    }

    public function addConsoleCommands()
    {
        $this->add($this->container->get('twitter_console.console.command.stream_command'));
    }
}
