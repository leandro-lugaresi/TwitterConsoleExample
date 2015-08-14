<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/config/parameters.yml'));

$app->run();
