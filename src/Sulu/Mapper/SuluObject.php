<?php

namespace Sulu\Mapper;

class SuluObject
{
    /**
     * @var Sulu\Mapper\ChildrenIterator
     */
    private $childIterator;

    /**
     * @var Sulu\Mapper\ParentProxy
     */
    private $parentProxy;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var DataStore
     */
    private $data = array();

    /**
     * @var ObjectMetadata
     */
    private $metadata;

    public function __construct()
    {
        $this->data = new ObjectData();
    }

    public function set($name, $value, $locale = null)
    {
        $this->data[$name] = $value;
    }

    public function get($name, $object)
    {
        return $this->data[$name];
    }

    public function setLocale($locale)
    {
        $this->data->setLocale($locale);
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getChildren()
    {
        return $this->childIterator;
    }

    public function getParnet()
    {
        return $this->parentProxy->getParent();
    }

    public function getUuid()
    {
        return $this->uuid;
    }
}
