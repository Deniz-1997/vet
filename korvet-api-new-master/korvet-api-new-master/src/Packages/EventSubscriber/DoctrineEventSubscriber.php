<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Cash\CashierSchedule;
use App\Entity\Cash\CashReceipt;
use App\Entity\Reference\Action;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use App\Entity\ReceiptItem;
use App\Entity\Shift;
use App\Entity\Reference\CashRegister;
use App\Entity\Reference\CashRegisterServer;

class DoctrineEventSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    protected $organizationEntityClass;

    /**
     * @var string
     */
    protected $unitEntityClass;

    /** @var string */
    private $roleEntityClass;

    /**
     * @var string
     */
    public $userEntityClass;

    /** @var string */
    private $productEntityClass;

    /** @var string */
    private $stockEntityClass;

    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * DoctrineEventSubscriber constructor.
     * @param AnnotationReader $annotationReader
     * @param null             $organizationEntityClass
     * @param null             $unitEntityClass
     */
    public function __construct(
        AnnotationReader $annotationReader,
        $roleEntityClass = null,
        $organizationEntityClass = null,
        $unitEntityClass = null,
        $userEntityClass = null,
        $productEntityClass = null,
        $stockEntityClass = null
    ) {
        $this->roleEntityClass = $roleEntityClass;
        $this->annotationReader = $annotationReader;
        $this->organizationEntityClass = $organizationEntityClass;
        $this->unitEntityClass = $unitEntityClass;
        $this->userEntityClass = $userEntityClass;
        $this->productEntityClass = $productEntityClass;
        $this->stockEntityClass = $stockEntityClass;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }


    protected function getConfig()
    {
        return [
            CashRegister::class => [
                [
                    'setting' => 'organization_entity_class',
                    'field' => 'organization',
                    'settings' => [],
                ],
            ],
            CashRegisterServer::class => [
                [
                    'setting' => 'unit_entity_class',
                    'field' => 'unit',
                    'settings' => [],
                ],
            ],
            CashierSchedule::class => [
                [
                    'setting' => 'user_entity_class',
                    'field' => 'cashier',
                    'settings' => [],
                ],
            ],
        ];
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getEntityClassName(string $name): string
    {
        $name = explode('_', $name);
        $entityName = $name[0];
        for ($i = 1; $i < count($name); $i++) {
            $entityName .= ucfirst($name[$i]);
        }

        if (isset($this->$entityName)) {
            return $this->$entityName;
        }

        return '';
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     * @throws \ReflectionException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
    {
        $metadata = $args->getClassMetadata();

        $namingStrategy = $args
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy();

        if ($metadata->getName() == CashierSchedule::class) {

            $namingStrategy = $args
                ->getEntityManager()
                ->getConfiguration()
                ->getNamingStrategy();

            $metadata->mapManyToOne([
                'targetEntity' => $this->userEntityClass,
                'fieldName' => 'cashier',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_cashier',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'cashier_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);
        }

        if ($metadata->getName() == CashRegister::class) {
            $namingStrategy = $args
                ->getEntityManager()
                ->getConfiguration()
                ->getNamingStrategy();

            $metadata->mapManyToOne([
                'targetEntity' => $this->organizationEntityClass,
                'fieldName' => 'organization',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_organization',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'organization_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);
        }

        if ($metadata->getName() == CashRegisterServer::class) {
            $metadata->mapManyToOne([
                'targetEntity' => $this->unitEntityClass,
                'fieldName' => 'unit',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_unit',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'unit_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);
        }

        if ($metadata->getName() == ReceiptItem::class && $this->productEntityClass) {
            $metadata->mapManyToOne([
                'targetEntity' => $this->productEntityClass,
                'fieldName' => 'product',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_product',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'product_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);
        }

        if ($metadata->getName() == ReceiptItem::class && $this->stockEntityClass) {
            $metadata->mapManyToOne([
                'targetEntity' => $this->stockEntityClass,
                'fieldName' => 'stock',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_stock',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'stock_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);
        }

        if ($metadata->getName() == Shift::class || $metadata->getName() == CashReceipt::class) {
            $namingStrategy = $args
                ->getEntityManager()
                ->getConfiguration()
                ->getNamingStrategy();

            $metadata->mapManyToOne([
                'targetEntity' => $this->userEntityClass,
                'fieldName' => 'creator',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_creator',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'creator_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);

            $metadata->mapManyToOne([
                'targetEntity' => $this->userEntityClass,
                'fieldName' => 'cashier',
                'joinTable' => [
                    'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_cashier',
                    'joinColumns' => [
                        [
                            'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => 'cashier_id',
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]);
        }

        if ($metadata->getName() == Action::class) {
            $metadata->mapManyToMany(['targetEntity' => $this->roleEntityClass, 'fieldName' => 'roles']);
        }
    }
}
