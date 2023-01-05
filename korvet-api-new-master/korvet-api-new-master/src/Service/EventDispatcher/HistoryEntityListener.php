<?php

namespace App\Service\EventDispatcher;

use App\Enum\BaseEnum;
use App\Interfaces\EnumInterface;
use App\Service\EntityResolver;
use App\Service\EventDispatcher\Event\HistoryEntityEvent;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\UnitOfWork;
use Exception;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;
use Gedmo\Tool\Wrapper\AbstractWrapper;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\User as SecurityCoreUser;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Model\User as AuthBundleUser;
use App\Entity\User;
use App\Packages\Annotation\History;
use App\Entity\HistoryEntity;
use Throwable;

/**
 * Class HistoryEntityListener
 */
class HistoryEntityListener extends LoggableListener
{
    const DATETIME_FORMAT = 'd-m-Y H:i:s';

    const EVENT_PRE_PERSIST_LOG = 'onPrePersistHistoryEntity';

    const EVENT_PRE_CREATE_LOG = 'onPreCreateHistoryEntity';

    const TRANSLATE_CHANGE = 'change';

    const TRANSLATE_PREFIX_MESSAGE = 'prefix_history';

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var EntityResolver
     */
    private EntityResolver $entityResolver;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    private LoggerInterface $logger;

    /**
     * HistoryEntityListener constructor.
     *
     * @param TokenStorageInterface    $tokenStorage
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityResolver           $entityResolver
     * @param TranslatorInterface      $translator
     * @param LoggerInterface          $logger
     */
    public function __construct(TokenStorageInterface $tokenStorage, EventDispatcherInterface $eventDispatcher, EntityResolver $entityResolver, TranslatorInterface $translator, LoggerInterface $logger) {
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityResolver = $entityResolver;
        $this->translator = $translator;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * Returns an objects changeset data
     *
     * @param LoggableAdapter $ea
     * @param object          $object
     * @param object          $logEntry
     *
     * @return array
     */
    protected function getObjectChangeSetData($ea, $object, $logEntry): array
    {
        $om = $ea->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $om);
        $meta = $wrapped->getMetadata();
        $config = $this->getConfiguration($om, $meta->name);
        $uow = $om->getUnitOfWork();
        $newValues = [];

        foreach ($ea->getObjectChangeSet($uow, $object) as $field => $changes) {
            try {
                if (empty($config['versioned']) || !in_array($field, $config['versioned'])) {
                    continue;
                }
                $value = $changes[1];
                $newValues[$field]['old'] = $changes[0];
                if ($value === $changes[0]) {
                    continue;
                }
                if ($meta->isSingleValuedAssociation($field) && $value) {
                    if ($wrapped->isEmbeddedAssociation($field)) {
                        $value = $this->getObjectChangeSetData($ea, $value, $logEntry);
                    } else {
                        $oid = spl_object_hash($value);
                        $wrappedAssoc = AbstractWrapper::wrap($value, $om);
                        $value = $wrappedAssoc->getIdentifier(false);
                        if (!is_array($value) && !$value) {
                            $this->pendingRelatedObjects[$oid][] = [
                                'log' => $logEntry,
                                'field' => $field,
                            ];
                        }
                    }
                }
                $newValues[$field]['new'] = $value;
            } catch (Exception $exception) {

            }
        }

        return $newValues;
    }

    /**
     * @param string          $action
     * @param object          $object
     * @param LoggableAdapter $ea
     *
     * @return HistoryEntity|null
     * @throws ReflectionException
     */
    protected function createLogEntry($action, $object, LoggableAdapter $ea)
    {
        /** @var ObjectManager $om */
        $om = $ea->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $om);
        $meta = $wrapped->getMetadata();
        $annotationHistory = $this->entityResolver->getData($object);
        // Filter embedded documents
        if (isset($meta->isEmbeddedDocument) && $meta->isEmbeddedDocument) {
            return null;
        }
        if ($config = $this->getConfiguration($om, $meta->name)) {
            $logEntryClass = $this->getLogEntryClass($ea, $meta->name);
            $logEntryMeta = $om->getClassMetadata($logEntryClass);
            /** @var LogEntry|HistoryEntity $logEntry */
            $event = new HistoryEntityEvent($logEntry = $logEntryMeta->newInstance(), $object);
            $this->eventDispatcher->dispatch($event, self::EVENT_PRE_CREATE_LOG, );
            // stop
            if (false === (bool)$event->isValid) {
                return null;
            }
            $userId = $clientId = null;
            $userHistory = new User();
            /** @var TokenStorage $token */
            if ($token = $this->tokenStorage->getToken()) {
                /** @var AuthBundleUser| SecurityCoreUser $user */
                $user = $token->getUser();
                if (method_exists($user, 'getId')) {
                    $userId = $user->getId();
                }
                try {
                    $clientId = null;
                    if($token->hasAttribute('authentication_information')){
                        $clientId = $this->tokenStorage->getToken()->getAttribute('authentication_information')->getClientId();
                    }
                } catch (Exception $exception) {
                }
                $userHistory->setUserId($userId);
                $userHistory->setClientId($clientId);
                $userHistory->setUsername($user->getUsername()?? null);
                if ($user instanceof AuthBundleUser) {
                    $userHistory->setUserFirstname($user->getName() ?? null);
                    $userHistory->setUserSurname($user->getSurname()?? null);
                    $userHistory->setUserPatronymic($user->getPatronymic()?? null);
                }
                $logEntry->setUser($userHistory);
            }

            $logEntry->setAction($action);
            $logEntry->setUsername($this->username);
            $logEntry->setObjectClass($meta->name);
            $logEntry->setLoggedAt();

            // check for the availability of the primary key
            /** @var UnitOfWork $uow */
            $uow = $om->getUnitOfWork();
            $objectHashKey = spl_object_hash($object);
            if ($action === self::ACTION_CREATE && $ea->isPostInsertGenerator($meta)) {
                $this->pendingLogEntryInserts[$objectHashKey] = $logEntry;
            } else {
                // change identifier
                $identifier = null;
                if ($annotationHistory instanceof History && $annotationHistory->fieldIdentifier) {
                    $fieldId = $annotationHistory->fieldIdentifier;
                    $method = 'get' . ucfirst($fieldId);
                    if (method_exists($object, $method)) {
                        $identifier = $object->$method();
                    }
                }
                $logEntry->setObjectId($identifier ?? $wrapped->getIdentifier());
            }
            $new = $diff = [];
            $reflectionClass = new ReflectionClass($object);
            $shortNameClass = $reflectionClass->getShortName();
            if ($action !== self::ACTION_REMOVE && isset($config['versioned'])) {
                $itemsDiff = [];
                $newValues = $this->getObjectChangeSetData($ea, $object, $logEntry);
                foreach ($newValues as $field => $values) {
                    $labelField = $shortNameClass.'.'.$field;

//                    if (!isset($newValues[$field]['new'])) {
//                        continue;
//                    }

                    if (!key_exists('new', $newValues[$field])) {
                        continue;
                    }

                    $newValue = $newValues[$field]['new'];
                    $oldValue = $newValues[$field]['old'];
                    if ($action !== self::ACTION_CREATE) {
                        $diff = $this->getDiff($labelField, $newValue, $oldValue);
                        if (!$diff) {
                            continue;
                        }
                        $itemsDiff[$this->translator->trans($labelField, [], 'propertys')] = $diff;
                        if ($newValue instanceof DateTime) {
                            $newValue = $newValue->format(self::DATETIME_FORMAT);
                        }
                    }
                    $new[] = [$labelField => $newValue];
                }
                if ($action === self::ACTION_CREATE) {
                    if (property_exists($object, 'jsonData')) {
                        if (method_exists($object, 'getJsonData')) {
                            $entryData = $object->getJsonData() ?? $new;
                            $logEntry->setData($entryData);
                        } else {
                            try {
                                $entryData = $object->jsonData ?? $new;
                                $logEntry->setData($entryData);
                            } catch (Exception $exception) {

                            }
                        }
                    }else {
                        $logEntry->setData($new);
                    }
                }
                $logEntry->setDiff($itemsDiff);
            }

            if ($action === self::ACTION_UPDATE && 0 === count($new)) {
                return null;
            }

            $version = 1;
            if ($action !== self::ACTION_CREATE) {
                $version = $ea->getNewVersion($logEntryMeta, $object);
                if (empty($version)) {
                    // was versioned later
                    $version = 1;
                }
            }
            $logEntry->setVersion($version);
            $this->prePersistLogEntry($logEntry, $object);
            if (!$logEntry->getComment()) {
                if ($comment = $this->createComment($object, $logEntry->getAction(), $new ?? [])) {
                    $logEntry->setComment(implode('; ', $comment));
                }
            }
            if (false === $event->isValid) {
                return null;
            }
            $om->persist($logEntry);
            $uow->computeChangeSet($logEntryMeta, $logEntry);

            return $logEntry;
        }

        return null;
    }

    /**
     * @param string $field
     * @param array $newData
     * @param array $oldData
     * @param array $changes
     *
     * @return array|void|null
     */
    public function getDiff(string $field, $newData, $oldData, &$changes = [])
    {
        try {
            if ($newData instanceof DateTime && $oldData instanceof DateTime) {
                if ($newData->getTimestamp() !== $oldData->getTimestamp()) {
                    return ['old' => $oldData->format(self::DATETIME_FORMAT), 'new' => $newData->format(self::DATETIME_FORMAT)];
                }
                return $changes;
            }
            if ($newData instanceof DateTime && is_null($oldData)) {
                return ['old' => $oldData, 'new' => $newData->format(self::DATETIME_FORMAT)];
            }
            if (is_null($newData) && $oldData instanceof DateTime ) {
                return ['old' => $oldData->format(self::DATETIME_FORMAT), 'new' => $newData];
            }

            if ($newData instanceof EnumInterface && $oldData instanceof EnumInterface) {
                $oldVal = $oldData->isNoValue() ? null : $oldData->code;
                $newVal = $newData->isNoValue() ? null : $newData->code;
                if ($oldVal !== $newVal) {
                    $classOldData = get_class($oldData);
                    $classNewData = get_class($newData);
                    return [
                        'old' => $this->translator->trans($classOldData::getLabelCode($oldData->code), [], 'enum'),
                        'new' => $this->translator->trans($classNewData::getLabelCode($newData->code), [], 'enum')];
                }
                return null;
            } elseif (!$newData instanceof EnumInterface && $oldData instanceof EnumInterface) {
                $oldVal = $oldData->isNoValue() ? null : $oldData->code;
                $newVal = $newData;
                $classOldData = get_class($oldData);
                if ($oldVal !== $newVal) {
                    return [
                        'old' => $this->translator->trans($classOldData::getLabelCode($oldData->code), [], 'enum'),
                        'new' => $this->translator->trans(BaseEnum::NULLABLE, [], 'enum'),
                    ];
                }
                return null;
            } elseif ($newData instanceof EnumInterface && !$oldData instanceof EnumInterface) {
                $oldVal = $oldData;
                $newVal = $newData->isNoValue() ? null : $newData->code;
                $classNewData = get_class($newData);

                if ($oldVal !== $newVal) {
                    return [
                        'old' => $this->translator->trans(BaseEnum::NULLABLE, [], 'enum'),
                        'new' => $this->translator->trans($classNewData::getLabelCode($newData->code), [], 'enum')
                    ];
                }
                return null;
            }
            if ($newData instanceof BaseEnum && ($oldData === null || empty($oldData))) {
                return ['old' => $this->translator->trans(BaseEnum::NULLABLE, [], 'enum'), 'new' => $this->translator->trans($newData->code, [], 'enum')];
            }

            if(isset($newData['id']) && is_object($oldData)){
                return ['old' => $oldData->getId(), 'new' => $newData['id']];
            }
            if (!is_object($newData) && !is_object($oldData)) {
                if(isset($newData['id']) && is_object($oldData)){
                    return ['old' => $oldData->getId(), 'new' => $newData['id']];
                }
                if ((is_string($oldData) && is_string($newData)) || (null === $oldData && is_string($newData))) {
                    if ($oldData !== $newData) {
                        return ['old' => $oldData, 'new' => $newData];
                    }
                }
                if ((is_int($newData) && is_string($oldData)) || (is_string($newData) && is_int($oldData))) {
                    if (((int)$newData !== (int)$oldData) && ((string)$newData !== (string)$oldData)) {
                        return ['old' => $oldData, 'new' => $newData];
                    }
                }
                if ((is_float($newData) || is_int($newData)) || (is_float($oldData) || is_int($oldData))) {
                    if ((float)$newData !== (float)$oldData) {
                        return ['old' => $oldData, 'new' => $newData];
                    }
                }
                if (is_bool($newData) && is_bool($oldData) && ($oldData !== $newData)) {
                    return ['old' => $oldData, 'new' => $newData];
                }
                if (is_string($oldData) && is_string($newData)) {
                    if ($oldData !== $newData) {
                        return ['old' => $oldData, 'new' => $newData];
                    }
                }
                if (is_array($newData) && is_array($oldData)) {
                    $keysNewData = array_keys($newData);
                    sort($keysNewData);
                    $keysOldData = array_keys($oldData);
                    sort($keysOldData);
                    if ($keysOldData === $keysNewData) {
                        foreach (array_keys($newData) as $key) {
                            if (isset($newData[$key], $oldData[$key])) {
                                if (!is_array($newData[$key])) {
                                    if (($diff = $this->getDiff($field, $newData[$key], $oldData[$key], $changes)) && ($diff['old'] !== $diff['new'])) {
                                        $changes[$field.'.'.$key] = $diff;
                                    }
                                } else {
                                    foreach ($newData[$key] as $newKey => $newValue) {
                                        if (isset($oldData[$key][$newKey])) {
                                            if (!is_array($oldData[$key][$newKey]) && $changeItem = $this->getDiff($field, $newData[$key][$newKey], $oldData[$key][$newKey], $changes)) {
                                                $changes[$field] = $changeItem;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    return $changes;
                }
            } else { /** @todo : доработать если будет необходимость */
                $new = $newData;
                $old = $oldData;
            }
        } catch (Exception | Throwable $exception) {
            $this->createLog($exception, ['method' => __METHOD__, 'args' => func_get_args()]);
        }
    }

    /**
     * @param Exception|Throwable $exception
     * @param $context
     */
    private function createLog($exception, $context)
    {
        $this->logger->warning($exception->getMessage(), $context);
    }

    /**
     * Handle any custom LogEntry functionality that needs to be performed
     * before persisting it
     *
     * @param HistoryEntity $logEntry The LogEntry being persisted
     * @param object        $object   The object being Logged
     *
     * @return HistoryEntityEvent
     */
    protected function prePersistLogEntry($logEntry, $object): HistoryEntityEvent
    {
        $event = new HistoryEntityEvent($logEntry, $object);
        $this->eventDispatcher->dispatch( $event, self::EVENT_PRE_PERSIST_LOG);

        return $event;
    }

    /**
     * @param object $object
     * @param string $action
     * @param array $newData
     *
     * @return array|null
     * @throws ReflectionException
     */
    private function createComment(object $object, string $action, array $newData): ?array
    {
        // disable comment for action "create"
        if (self::ACTION_CREATE === $action) {
            return null;
        }
        $comments = [];

        if (!empty($newData)) {
            foreach ($newData as $property => $value) {
                if (is_int($property)) {
                    foreach ($value as $p => $v) {
                        $comments[] = $this->createItemComment($action, $p, $v, $object);
                    }
                } else {
                    $comments[] = $this->createItemComment($action, $property, $value, $object);
                }
            }

        }

        return $comments;
    }

    /**
     * @param string $action
     * @param string $property
     * @param mixed $value
     * @param object $object - loggable object
     *
     * @return string
     */
    private function createItemComment(string $action, string $property, $value, object $object): string
    {
        $tr = $this->translator;

        return $tr->trans($property, [], 'propertys') . ': ' . $this->createValueForComment($value);
    }

    /**
     * @param mixed  $value
     * @param string $valueString
     *
     * @return mixed|string
     */
    private function createValueForComment($value, &$valueString = ''): string
    {
        if (is_array($value) || $valueString) {
            foreach ($value as $key => $item) {
                if (is_int($key) && is_string($item)) {
                    $valueString .= $this->translator->trans($item, [], 'propertys').';';
                } elseif (is_string($key) && is_string($item)) {
                    $keyStr = $this->translator->trans($key, [], 'propertys');
                    $valueString .= "$keyStr : $item;";
                } elseif (is_array($item) && is_string($key) && (array_key_exists('code', $item) && array_key_exists('title', $item))) {
                    // Enum
                    $keyStr = $this->translator->trans($key, [], 'propertys');
                    $valueString .= "$keyStr: {$item['code']} ({$item['title']});";
                } else {
                    if (is_array($item)) {
                        $this->createValueForComment($item, $valueString);
                    }
                }
            }

            return $valueString;
        }
        if ($value instanceof DateTime) {
            return $value->format(self::DATETIME_FORMAT);
        }
        if ($value instanceof EnumInterface) {
            return $this->translator->trans($value->code ?? BaseEnum::NULLABLE, [], 'enum');
        }

        return empty($value) ? '' : $value;
    }
}
