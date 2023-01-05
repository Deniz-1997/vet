<?php

namespace App\Repository;

use App\Entity\HistoryEntity;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository as GedmoLogEntryRepository;
use Gedmo\Tool\Wrapper\EntityWrapper;

/**
 * Class LogEntryRepository
 */
class HistoryEntityRepository extends GedmoLogEntryRepository
{
    /**
     * Reverts given $entity to $revision by
     * restoring all fields from that $revision.
     * After this operation you will need to
     * persist and flush the $entity.
     *
     * @param object  $entity
     * @param integer $version
     *
     * @throws \Gedmo\Exception\UnexpectedValueException
     *
     * @return void
     */
    public function revert($entity, $version = 1)
    {
        $wrapped = new EntityWrapper($entity, $this->_em);
        $objectMeta = $wrapped->getMetadata();
        $objectClass = $objectMeta->name;
        $meta = $this->getClassMetadata();
        $dql = "SELECT log FROM {$meta->name} log";
        $dql .= " WHERE log.objectId = :objectId";
        $dql .= " AND log.objectClass = :objectClass";
        $dql .= " AND log.version <= :version";
        $dql .= " ORDER BY log.version ASC";

        $objectId = $wrapped->getIdentifier();
        $q = $this->_em->createQuery($dql);
        $q->setParameters(compact('objectId', 'objectClass', 'version'));
        $logs = $q->getResult();

        if ($logs) {
            $config = $this->getLoggableListener()->getConfiguration($this->_em, $objectMeta->name);
            $fields = $config['versioned'];
            $filled = false;
            while (($log = array_pop($logs)) && !$filled) {
                /** @var HistoryEntity $log */
                if ($data = $log->getNewValue()) {
                    foreach ($data as $field => $value) {
                        if (in_array($field, $fields)) {
                            $this->mapValue($objectMeta, $field, $value);
                            $wrapped->setPropertyValue($field, $value);
                            unset($fields[array_search($field, $fields)]);
                        }
                    }
                }
                $filled = count($fields) === 0;
            }
            /*if (count($fields)) {
                throw new \Gedmo\Exception\UnexpectedValueException('Could not fully revert the entity to version: '.$version);
            }*/
        } else {
            throw new \Gedmo\Exception\UnexpectedValueException('Could not find any log entries under version: ' . $version);
        }
    }
}
