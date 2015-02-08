<?php

namespace Sulu\Mapper\Operation;

class ReorderOperation extends Operation
{
    private $childName;
    private $destName;
    private $before;

    public function __construct(SuluObject $object, $childName, $destName, $before)
    {       
        parent::__construct($object);
        $this->childName = $childName;
        $this->destName = $destName;
        $this->before = $before;
    }

    public function getTargetPath()
    {
        return $this->childName;
    }

    public function getChildName() 
    {
        return $this->childName;
    }

    public function getDestName() 
    {
        return $this->destName;
    }

    public function getBefore() 
    {
        return $this->before;
    }
}
