<?php

namespace Sulu\Mapper\Operation;

class OperationStack
{
    private $operations = array();

    public function push(OperationInterface $operation)
    {
        array_push($this->operations, $operation);
    }

    public function shift()
    {
        return array_shift($this->operations);
    }
}
