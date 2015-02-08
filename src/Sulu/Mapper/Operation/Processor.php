<?php

namespace Sulu\Mapper\Operation;

class Processor
{
    private $registry;
    private $session;

    public function __constuct(ObjectRegistry $registry, SessionInterface $session)
    {
        $this->registry = $registry;
        $this->session = $session;
    }

    public function process(OperationStack $stack)
    {
        while($operation = $stack->shift()) {
            switch (get_class($operation)) {
                case 'Sulu\Mapper\Operation\MoveOperation':
                    $this->processMove($operation);
                    continue;
                case 'Sulu\Mapper\Operation\PersistOperation':
                    $this->processPersist($operation);
                    continue;
                case 'Sulu\Mapper\Operation\RemoveOperation':
                    $this->processRemove($operation);
                    continue;
                case 'Sulu\Mapper\Operation\ReorderOperation':
                    $this->processReorder($operation);
                    continue;
                default:
                    throw new \InvalidArgumentException(sprintf(
                        'Do not know how to process operation of type "%s"',
                        get_class($operation)
                    ));
            }
        }
    }

    public function processMove(MoveOperation $operation)
    {
        $object = $operation->getObject();
        $destPath = $operation->getTargetPath();

        $phpcrNode = $this->registry->objectToNode($object);
        $this->session->move($phpcrNode->getPath(), $destPath);
    }

    public function processPersist(PersistOperation $operation)
    {
        $object = $operation->getObject();
        $phpcrNode = $this->getPhpcrNode($object);

        
        $this->mapper->objectToNode($object, $phpcrNode);
    }

    public function processRemove(RemoveOperation $operation)
    {
        $object = $operation->getObject();
        $this->getPhpcrNode($object)->remove();
    }

    public function processReorder(ReorderOperation $operation)
    {
        $object = $operation->getObject();
        $childName = $operation->getChildName();
        $destName = $operation->getDestName();
        $before = $operation->getBefore();

        if (false === $before) {
            throw new \InvalidArgumentException(
                'Ordering "after" is not currently supported'
            );
        }

        $phpcrNode = $this->registry->objectToNode($object);
        $phpcrNode->orderBefore($childName, $destName);
    }

    private function getPhpcrNode($object)
    {
        $state = $this->registry->getState($object);

        if ($state == ObjectRegistry::STATE_MANAGED) {
            return $this->registry->objectToNode($object);
        }

        $parentObject = $object->getParent();

        if (!$parentObject) {
            throw new Exception\OperationException(
                'Object has no parent, cannot perist objects without a parent.'
            );
        }

        $state = $this->registry->getState($parentObject);

        if ($state !== ObjectRegistry::STATE_MANAGED) {
            throw new Exception\OperationException(
                'Parent object is not managed. You must persist parent objects before child objects'
            );
        }

        $parentNode = $this->registry->objectToNode($parentNode);

        return $parentNode->addNode($object->getName(), $object->getMetadata()->getPhpcrType());
    }
}
