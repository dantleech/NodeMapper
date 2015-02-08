<?php

namespace Sulu\Mapper;

use PHPCR\RepositoryException;
use PHPCR\ItemNotFoundException;

class ObjectManager
{
    private $nodeMapper;
    private $session;
    private $operationProcessor;
    private $operationStack;

    public function __construct(
        SessionInterface $session,
        NodeMapper $mapper,
        OperationProcessor $operationProcessor
    ) {
        $this->nodeMapper = $mapper;
        $this->session = $session;
        $this->operationProcessor = $operationProcessor;
        $this->operationStack = new OperationStack();
    }

    public function find($pathOrId)
    {
        $node = $this->getNode($pathOrId);

        return $this->mapper->nodeToObject($node);
    }

    public function persist(SuluObject $object)
    {
        $this->operationStack->push(new PersistOperation($object));
    }

    public function move(SuluObject $object, $targetPath)
    {
        $this->operationStack->push(new MoveOperation($object, $targetPath));
    }

    public function reorder(SuluObject $object, $childName, $destName, $before)
    {
        $this->operationStack->push(new ReorderOperation($object, $childName, $destName, $before));
    }

    public function remove(SuluObject $object)
    {
        $this->operationStack->push(new RemoveOperation($object));
    }

    public function query($query)
    {
        throw new \BadMethodCallException('Not Implemented');

        $nodes = $this->queryManager->execute($query);

        return new NodeIterator($nodes);
    }

    public function flush()
    {
        $this->operationProcessor->process($this->operationStack);
    }

    private function getNode($pathOrId)
    {
        try {
            if (UUIDHelper::isUuid($pathOrId)) {
                return $this->session->getNodeByIdentifier($pathOrId);
            }

            return $this->session->getNode($pathOrId);

        } catch (\PHPCR\RepositoryException $e) {
            throw new NodeNotFoundException(sprintf(
                'Could not find node with path or identifier "%s"',
                $pathOrId
            ), null, $e);
        }
    }
}
