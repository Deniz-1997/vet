<?php

namespace App\Validator;

use Countable;
use DateTimeInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Iterator;
use IteratorAggregate;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use App\Validator\Constraint\UniqueEntity;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use function count;
use function get_class;
use function is_array;
use function is_object;
use function is_string;
use function method_exists;

class UniqueEntityValidator extends \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;

        parent::__construct($registry);
    }

    /**
     * @param object $entity
     * @param Constraint $constraint
     *
     * @throws Exception
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueEntity');
        }

        if (!is_array($constraint->fields) && !is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if (null !== $constraint->errorPath && !is_string($constraint->errorPath)) {
            throw new UnexpectedTypeException($constraint->errorPath, 'string or null');
        }

        $fields = (array) $constraint->fields;

        if (0 === count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        if (null === $entity) {
            return;
        }

        if ($constraint->em) {
            $em = $this->registry->getManager($constraint->em);

            if (!$em) {
                throw new ConstraintDefinitionException(sprintf('Object manager "%s" does not exist.', $constraint->em));
            }
        } else {
            $em = $this->registry->getManagerForClass(get_class($entity));

            if (!$em) {
                throw new ConstraintDefinitionException(sprintf('Unable to find the object manager associated with an entity of class "%s".', get_class($entity)));
            }
        }

        $class = $em->getClassMetadata(get_class($entity));
        /* @var $class ClassMetadata */

        $criteria = array();
        $hasNullValue = false;

        foreach ($fields as $fieldName) {
            if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
                throw new ConstraintDefinitionException(sprintf('The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.', $fieldName));
            }

            $fieldValue = $class->reflFields[$fieldName]->getValue($entity);

            if (null === $fieldValue) {
                $hasNullValue = true;
            }

            if ($constraint->ignoreNull && null === $fieldValue) {
                continue;
            }

            $criteria[$fieldName] = $fieldValue;

            if (null !== $criteria[$fieldName] && $class->hasAssociation($fieldName)) {
                /* Ensure the Proxy is initialized before using reflection to
                 * read its identifiers. This is necessary because the wrapped
                 * getter methods in the Proxy are being bypassed.
                 */
                $em->initializeObject($criteria[$fieldName]);
            }
        }

        // validation doesn't fail if one of the fields is null and if null values should be ignored
        if ($hasNullValue && $constraint->ignoreNull) {
            return;
        }

        // skip validation if there are no criteria (this can happen when the
        // "ignoreNull" option is enabled and fields to be checked are null
        if (empty($criteria)) {
            return;
        }

        if (null !== $constraint->entityClass) {
            /* Retrieve repository from given entity name.
             * We ensure the retrieved repository can handle the entity
             * by checking the entity is the same, or subclass of the supported entity.
             */
            $repository = $em->getRepository($constraint->entityClass);
            $supportedClass = $repository->getClassName();

            if (!$entity instanceof $supportedClass) {
                throw new ConstraintDefinitionException(sprintf('The "%s" entity repository does not support the "%s" entity. The entity should be an instance of or extend "%s".', $constraint->entityClass, $class->getName(), $supportedClass));
            }
        } else {
            $repository = $em->getRepository(get_class($entity));
        }

        if (property_exists(get_class($entity), 'deleted')) {
            $criteria['deleted'] = false;
        }
        $result = $repository->{$constraint->repositoryMethod}($criteria);

        if ($result instanceof IteratorAggregate) {
            $result = $result->getIterator();
        }

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof Iterator) {
            $result->rewind();
            if ($result instanceof Countable && 1 < count($result)) {
                $result = array($result->current(), $result->current());
            } else {
                $result = $result->current();
                $result = null === $result ? array() : array($result);
            }
        } elseif (is_array($result)) {
            reset($result);
        } else {
            $result = null === $result ? array() : array($result);
        }

        /* If no entity matched the query criteria or a single entity matched,
         * which is the same as the entity being validated, the criteria is
         * unique.
         */
        if (!$result || (1 === count($result) && current($result) === $entity)) {
            return;
        }

        $errorPath = null !== $constraint->errorPath ? $constraint->errorPath : $fields[0];
        $invalidValue = isset($criteria[$errorPath]) ? $criteria[$errorPath] : $criteria[$fields[0]];

        $this->context->buildViolation($constraint->message)
            ->atPath($errorPath)
            ->setParameter('{{ value }}', $this->formatWithIdentifiers($em, $class, $invalidValue))
            ->setInvalidValue($invalidValue)
            ->setCode(UniqueEntity::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }

    private function formatWithIdentifiers(ObjectManager $em, ClassMetadata $class, $value): string
    {
        if (!is_object($value) || $value instanceof DateTimeInterface) {
            return $this->formatValue($value, self::PRETTY_DATE);
        }

        if (method_exists($value, '__toString')) {
            return (string) $value;
        }

        if ($class->getName() !== $idClass = get_class($value)) {
            // non unique value might be a composite PK that consists of other entity objects
            if ($em->getMetadataFactory()->hasMetadataFor($idClass)) {
                $identifiers = $em->getClassMetadata($idClass)->getIdentifierValues($value);
            } else {
                // this case might happen if the non unique column has a custom doctrine type and its value is an object
                // in which case we cannot get any identifiers for it
                $identifiers = array();
            }
        } else {
            $identifiers = $class->getIdentifierValues($value);
        }

        if (!$identifiers) {
            return sprintf('object("%s")', $idClass);
        }

        array_walk($identifiers, function (&$id, $field) {
            if (!is_object($id) || $id instanceof DateTimeInterface) {
                $idAsString = $this->formatValue($id, self::PRETTY_DATE);
            } else {
                $idAsString = sprintf('object("%s")', get_class($id));
            }

            $id = sprintf('%s => %s', $field, $idAsString);
        });

        return sprintf('object("%s") identified by (%s)', $idClass, implode(', ', $identifiers));
    }
}
