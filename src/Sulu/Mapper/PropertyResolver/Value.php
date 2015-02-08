<?php

namespace Sulu\Mapper\PropertyResolver;

class Value
{
    private $value;
    private $locale;

    public function __construct($value, $locale = null)
    {
        $this->value = $value;
        $this->locale = $locale;
    }
}
