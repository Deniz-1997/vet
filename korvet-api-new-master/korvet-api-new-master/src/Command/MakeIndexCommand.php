<?php

namespace App\Command;

use App\Entity\Appointment\Appointment;
use App\Entity\Owner;
use App\Entity\Pet\Pet;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ImportFromFileCommand
 */
class MakeIndexCommand extends Command
{
    protected static $defaultName = 'app:make-index';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var ParameterBagInterface */
    private ParameterBagInterface $params;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $params
     */
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->entityManager = $entityManager;
        $this->params = $params;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Make SQL Indexs');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->makeIndexAppointment($output);
        $output->writeln('done');
        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     */
    protected function makeIndexPet(OutputInterface $output)
    {
        $params = [];
        $classMetadata = $this->entityManager->getClassMetadata(Pet::class);

        foreach ($classMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);

            foreach ($classMetadata->getAssociationMappings() as $associationMapping) {
                if ($fieldName === $associationMapping['fieldName']) {
                    // continue; //Ignore
                }
            }


            if (in_array($fieldMapping['type'], [Types::STRING, Types::TEXT])) {
                $params[] = 'coalesce(pets.' . $fieldMapping['columnName'] . ', \' \')';
            }

        }

        $tsvector = 'to_tsvector(\'russian\', ' . implode(' || \' \' || ', $params)
            . ' || \' \' || coalesce(array_to_string(array(SELECT p2_.value FROM pet.pets_identifiers p2_ WHERE pet.pets.id = p2_.pet_id),\', \'), \' \') ) ';

        $insert = 'INSERT INTO pet.pet_index_search (pet_id, index) '
            . ' SELECT pet.pets.id, ' . $tsvector
            . ' FROM pet.pets  '
            //. ' LIMIT 10'
            . ' ON CONFLICT (pet_id) DO UPDATE'
            . ' SET index = ( SELECT ' . $tsvector . ' FROM pet.pets WHERE pet.pets.id = pet.pet_index_search.pet_id)';


        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($insert);
        $stmt->execute([]);
        $output->writeln('Update Pet Index: ' . $stmt->rowCount());
    }

    /**
     * @param OutputInterface $output
     */
    protected function makeIndexAppointment($output)
    {
        $params = [];
        $classMetadata = $this->entityManager->getClassMetadata(Appointment::class);

        foreach ($classMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);

            foreach ($classMetadata->getAssociationMappings() as $associationMapping) {
                if ($fieldName === $associationMapping['fieldName']) {
                    // continue; //Ignore
                }
            }


            if (in_array($fieldMapping['type'], [Types::STRING, Types::TEXT])) {
                $params[] = 'coalesce(appointments.' . $fieldMapping['columnName'] . ', \' \')';
            }

        }

        $tsvector = 'to_tsvector(\'russian\', ' . implode(' || \' \' || ', $params)
            . ') ';

        $insert = 'INSERT INTO appointment.appointment_index_search (appointment_id, index) '
            . ' SELECT appointments.id, ' . $tsvector
            . ' FROM appointment.appointments  '
            . ' ON CONFLICT (appointment_id) DO UPDATE'
            . ' SET index = ( SELECT ' . $tsvector . ' FROM appointment.appointments WHERE appointments.id = appointment_index_search.appointment_id)';

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($insert);
        $stmt->execute([]);
        $output->writeln('Update Appointment Indexs: ' . $stmt->rowCount());
    }
}
