<?php
namespace Omeka\Api\Adapter\Entity;

use Doctrine\ORM\QueryBuilder;
use Omeka\Model\Entity\EntityInterface;
use Omeka\Model\Entity\ResourceClass;
use Omeka\Stdlib\ErrorStore;

class ItemAdapter extends AbstractEntityAdapter
{
    /**
     * {@inheritDoc}
     */
    public function getResourceName()
    {
        return 'items';
    }

    /**
     * {@inheritDoc}
     */
    public function getRepresentationClass()
    {
        return 'Omeka\Api\Representation\Entity\ItemRepresentation';
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityClass()
    {
        return 'Omeka\Model\Entity\Item';
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, EntityInterface $entity,
        ErrorStore $errorStore
    ) {
        if (isset($data['owner']['id'])) {
            $owner = $this->getAdapter('users')
                ->findEntity($data['owner']['id']);
            $entity->setOwner($owner);
        }
        if (isset($data['resource_class']['id'])) {
            $resourceClass = $this->getAdapter('resource_classes')
                ->findEntity($data['resource_class']['id']);
            $entity->setResourceClass($resourceClass);
        }
        if (isset($data['media']) && is_array($data['media'])) {
            $mediaAdapter = $this->getAdapter('media');
            foreach ($data['media'] as $mediaData) {
                if (isset($mediaData['id'])) {
                    $media = $mediaAdapter->findEntity(array(
                        'id' => $mediaData['id'],
                        // media cannot be reassigned
                        'item' => $entity->getId(),
                    ));
                    $mediaAdapter->hydrateEntity(
                        'update', $mediaData, $media, $errorStore
                    );
                } else {
                    $mediaEntityClass = $mediaAdapter->getEntityClass();
                    $media = new $mediaEntityClass;
                    $mediaAdapter->hydrateEntity(
                        'create', $mediaData, $media, $errorStore
                    );
                    $entity->addMedia($media);
                }
            }
        }
        $valueHydrator = new ValueHydrator($this);
        $valueHydrator->hydrate($data, $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function buildQuery(array $query, QueryBuilder $qb)
    {}

    /**
     * {@inheritDoc}
     */
    public function validate(EntityInterface $entity, ErrorStore $errorStore,
        $isPersistent
    ) {}
}
