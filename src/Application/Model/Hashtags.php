<?php

namespace Application\Model;

class Hashtags
{
    private $mongoDB;

    public function __construct(\MongoDB $mongoDB)
    {
        $this->mongoDB = $mongoDB;
    }

    public function get(array $filters = array())
    {
        return $this->getCollection()->find($filters);
    }

    public function save($document)
    {
        return $this->getCollection()->save($document);
    }

    private function getCollection()
    {
        return $this->mongoDB->hashtags;
    }


}
