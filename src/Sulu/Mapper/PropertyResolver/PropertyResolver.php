<?php

namespace Sulu\Mapper\PropertyResolver;

class PropertyResolver
{
    private $languageNamespace = 'i18n';
    private $defaultNamespace = 'sulu';

    public function resolveValues(PropertyMetadata $propertyMetadata, $node)
    {
        $value = new Value();

        if ($propertyMetadata->isLocalized()) {
            $values = $this->getLocalizedPropertyValues($node, $propertyMetadata->name);

            foreach ($values as $locale => $value) {
                $value = new Value($value, $locale);
                $values[] = $value;
            }

            return $values;
        }

        $value = $this->getPropertyValue($node, $propertyMetadata->name);

        return array(new Value($value));
    }

    public function getPropertyValue($node, $name)
    {
        $phpcrName = sprintf('%s:%s', $this->defaultNamespace, $name);

        return $node->getPropertyValueWithDefault($phpcrName, null);
    }

    /**
     * Return all the localized values of the localized property indicated
     * by $name
     *
     * @param NodeInterface $node
     * @param string $name  Name of localized property
     */
    public function getLocalizedPropertyValues(NodeInterface $node, $name)
    {
        $values = array();
        foreach ($node->getProperties() as $property) {
            /** @var PropertyInterface $property */
            preg_match('/^' . $this->languageNamespace . ':([a-zA-Z_]*?)-' . $name . '/', $property->getName(), $matches);

            if ($matches) {
                $values[$matches[1]] = $property->getValue();
            }
        }

        return $values;
    }
}
