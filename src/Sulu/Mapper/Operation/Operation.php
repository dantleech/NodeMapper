<?php

namespace Sulu\Mapper\Operation;

class Operation
{
    protected $object;

    public function __construct(SuluObject $object)
    {       
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }
}
