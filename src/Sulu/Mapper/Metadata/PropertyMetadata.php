<?php

namespace Sulu\Mapper\Metadata;

class PropertyMetadata
{
    const PROP_NS = 'sulu';
    const I18N_NS = 'i18n';

    public $isLocalized;
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
