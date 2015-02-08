<?php

namespace Sulu\Mapper;

class NodeMapper
{
    private $metadataFactory;

    public function __construct(MetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function nodeToObject(NodeInterface $node)
    {
        $nodeUuid = $node->getIdentifier();

        if (!UUIDHelper::isUUID($nodeUuid)) {
            throw new Exception\MapperException(sprintf(
                'Node at path "%s" has no UUID. All managed nodes must have the mix:referenceable mixin',
                $node->getPath()
            ));
        }

        $object = $this->objectRegistry->fromUuid($nodeUuid);

        if ($object) {
            return $object;
        }

        $metadata = $this->metadataFactory->getMetadataForType($node->getPrimaryNodeType());

        $this->createNewObject($node, $metadata);
        $this->objectRegistry->register($object);

        $mappedProperties = $metadata->getProperties();

        foreach ($mappedProperties as $name => $mappedProperty) {
            $values = $propertyResolver->resolveValues($mappedProperty, $node);

            foreach ($values as $value) {
                $object->getData()->set($name, $value->getValue(), $value->getLocale());
            }
        }

        return $object;
    }

    private function createNewObject(NodeInterface $node, Metadata $metadata)
    {
        $object = $metadata->getObjectReflection()->newInstance();
        $metadata->setObjectValue($object, 'uuid', $node->getIdentifier());
        $object->getData()->setMetadata($metadata);

        return $object;
    }
}
