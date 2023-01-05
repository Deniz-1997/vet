<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Velhron\DadataBundle\Exception\DadataException;
use Velhron\DadataBundle\Service\DadataClean;


class DaDataCleanCommand extends Command
{
    protected static $defaultName = 'app:dadata:clean';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var DadataClean  */
    private DadataClean $dadataClean;

    /** @var LoggerInterface */
    private $logger;

    /**
     * DaDataCleanCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param DadataClean $dadataClean
     */
    public function __construct(EntityManagerInterface $entityManager, DadataClean $dadataClean, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->dadataClean =$dadataClean;
        $this->entityManager = $entityManager;
        parent::__construct(self::$defaultName);
    }
    protected function configure()
    {
        $this->setDescription('Проводим валидацию данных по ФИО');
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->dadataCleaning($output);
        return Command::SUCCESS;

    }

    /**
     * @param $output
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     * @throws \Velhron\DadataBundle\Exception\InvalidConfigException
     */
    protected function dadataCleaning($output)
    {
        $sql = 'select id, name from public.owners order by id ASC';
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery();
        $result = $fetch->fetchAllAssociative();
        $count = $fetch->rowCount();
        $counter = 0;
        $percent = 0;
        $percentCount = 0;
        $startOwner = '';
        foreach ($result as $owner)
        {
            try {
                if ($counter == 0) {
                    $startOwner = $owner['id'] . '  ' . $owner['name'];
                }
                $percentCount = floor($counter / $count *100);
                $id = $owner['id'];
                $response = $this->dadataClean->cleanName($owner['name']);
                $fullName = $response->result;
                $name = $response->name;
                $surname = $response->surname;
                $patronymic = $response->patronymic;
                $gender = $response->gender;
                if ($name == NULL || $surname == NULL )
                {
                    continue;
                }
                if ($patronymic == NULL)
                {
                    $patronymic = '';
                }
                switch ($gender)
                {
                    case 'М':
                        $gender = 'MALE';
                        break;
                    case 'Ж':
                        $gender = 'FEMALE';
                        break;
                    case 'НД':
                        $gender = '';
                        break;
                }
                $sql = 'UPDATE public.owners SET name= :fullName , full_name_name= :name, full_name_last_name= :surname, full_name_middle_name= :patronymic, gender= :gender  WHERE id = :id';
                $conn = $this->entityManager->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'fullName' => $fullName,
                    'name' => $name,
                    'surname' => $surname,
                    'patronymic' => $patronymic,
                    'gender' => $gender,
                    'id' => $id
                ]);
                $counter ++;
                if ((int)$percentCount == $percent)
                {
                    $output->writeln("Standardization: " . $percent);
                    $percent += 10;

                }
                $this->logger->info('SUCCESS', $owner);
            } catch (DadataException $e) {
                $code = $e->getCode();
                $message = $e->getMessage();
                $output->writeln('Код: ' . $code . ' ' . 'Текст: ' . $message);
                $output->writeln('Первый владелец :  ' . $startOwner);
                $output->writeln('Прерывание команды произошло на пользователе:  ' . $owner['id'] . '  ' . $owner['name']);
                $this->logger->error('ERROR', $owner);
                return;
            }
        }
        $output->writeln('Standardization: SUCCESS');
    }

}
