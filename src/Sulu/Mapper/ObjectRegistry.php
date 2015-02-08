<?php

namespace Sulu\Mapper;

class ObjectRegistry
{
    private $uuidToObject = array();
    private $objectToNode = array();

    public function uuidToObject($uuid)
    {
        if (!isset($this->uuidToObject[$uuid])) {
            return null;
        }

        return $this->uuidToObject[$uuid];
    }

    public function objectToNode(SuluObject $object)
    {
        $objectId = $this->getObjectId($object);

        if (!isset($this->objectToNode[$objectId])) {
            return null;
        }

        return $this->objectToNode[$objectId];
    }

    public function register(SuluObject $object)
    {
        $this->uuidToObject[$object->getUuid] = $object;
        $this->objectToNode[$this->getObjectHash($object)] = $object->getNode();
    }

    private function getObjectId($object)
    {       
        return spl_object_hash($object);
    }
}
