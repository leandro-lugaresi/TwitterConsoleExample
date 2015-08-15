<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['mongo-connection'] = new MongoClient($app['mongodb_uri']);
$app['mongo-database'] = $app['mongo-connection']->$app['mongodb_database'];

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/config/parameters.yml'));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Application/views',
));

$app->get('/', function () use ($app) {
    return $app['twig']->render('home.twig', array(

    ));
});

$app->get('/hashtag/{:hash}', function ($hash) use ($app) {

    return $app['twig']->render('hash.twig', array(
        'hash' => $hash
    ));
});
$app->run();
