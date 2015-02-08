<?php

namespace Sulu\Mapper\Metadata;

class Metadata
{
    /**
     * @var PropertyMetadata[]
     */
    public $properties = array();

    /**
     * @var string
     */
    public $objectClass;

    /**
     * @var string
     */
    public $phpcrType;

    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    public function getObjectClass()
    {
        return $this->objectClass;
    }

    public function getObjectReflection()
    {
        if (isset($this->reflection)) {
            return $this->reflection;
        }

        return new \ReflectionObject($this->getObjectClass());
    }

    public function getPhpcrType()
    {
        return $this->phpcrType;
    }

    public function setObjectValue($object, $propertyName, $value)
    {
        $refl = $this->getObjectReflection();
        $prop = $refl->getProperty($propertyName);
        $prop->setAccessible(true);
        $prop->setValue($object, $propertyName, $value);
    }
}
