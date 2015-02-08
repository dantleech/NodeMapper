<?php

namespace Sulu\Mapper\Metadata\Factory;

class ArrayFactory
{
    private $mapping;

    public function __construct($mapping = array())
    {
        $this->mapping = $mapping;
    }

    public function getMetadataForType($type)
    {
        if (!isset($this->mapping[$type])) {
            throw new Exception\MetadataException(sprintf(
                'No mapping found for node type "%s"',
                $type
            ));
        }

        $mapping = $this->mapping[$type];
        $this->validateAndCompleteMapping($mapping);

        $metadata = new ObjectMetadata();
        $metadata->objectClass = $mapping['object_class'];
        $metadata->phpcrType = $type;

        foreach ($mapping['properties'] as $name => $property) {
            $propertyMetadata = new PropertyMetadata($name);
            $propertyMetadata->isLocalized = $property['is_localized'];
            $metadata->properties[] = $propertyMetadata;
        }

        return $metadata;
    }

    protected function validateAndCompleteMapping(&$mapping)
    {
        foreach (array(
            'object_class'
        ) as $required) {
            if (!isset($mapping[$required])) {
                throw new Exception\MetadataException(sprintf(
                    'Mapping key "%s" is mandatory. (when mapping type "%s"',
                    $required
                ));
            }
        }

        $mapping = array_merge(
            array(
                'properties' = array()
            ),
            $mapping
        );

        foreach ($mapping['properties'] as &$property) {
            $property = array_merge(array(
                'is_localized' => false,
            ), $property);
        }

        return $mapping;
    }
}
