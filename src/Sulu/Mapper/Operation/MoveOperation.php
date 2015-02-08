<?php

namespace Sulu\Mapper\Operation;

class MoveOperation extends Operation
{
    private $targetPath;

    public function __construct(SuluObject $object, $targetPath)
    {       
        parent::__construct($object);
        $this->targetPath = $targetPath;
    }

    public function getTargetPath()
    {
        return $this->targetPath;
    }
}
