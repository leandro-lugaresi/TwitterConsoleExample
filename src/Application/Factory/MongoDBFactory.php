<?php

namespace Application\Factory;

class MongoDBFactory
{
    public static function createMongoDB(\MongoClient $client, $database)
    {
        return $client->$database;
    }
}
