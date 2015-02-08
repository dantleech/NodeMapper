<?php

namespace Sulu\Mapper;

class ObjectData
{
    private $metadata;

    private $translatedData = array();

    private $locale;

    public function setMetadata(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    public function set($key, $value)
    {
        if ($this->metadata->getProperty[$key]->isLocalized()) {
            if (!isset($this->translatedData[$this->getLocale()])) {
                $this->translatedData[$this->getLocale()] = array();
            }
            $this->translatedData[$this->getLocale()][$key] = $value;
            return;
        }

        $this->data[$key] = $value;
    }

    public function get($key)
    {
        if (!$this->metadata->hasProperty($key)) {
            throw new \InvalidArgumentException(sprintf(
                'Trying to get non-mapped property "%s"',
                $key
            ));
        }

        if ($this->metadata->getProperty[$key]->isLocalized()) {

            if (!isset($this->translatedData[$this->getLocale()])) {
                return null;
            }

            return $this->translatedData[$this->getLocale()][$key];
        }

        if (!isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }
}
