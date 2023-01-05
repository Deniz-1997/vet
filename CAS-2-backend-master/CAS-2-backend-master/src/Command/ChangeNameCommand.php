<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ChangeNameCommand extends Command
{
    use OutputStringCommandTrait;
    
    protected static $defaultName = 'app:change:name';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $defaultEntityManager;



    /**
     * DaDataCleanCommand constructor.
     * @param EntityManagerInterface $defaultEntityManager
     */
    public function __construct(EntityManagerInterface $defaultEntityManager)
    {

        $this->defaultEntityManager = $defaultEntityManager;
        parent::__construct(self::$defaultName);
    }
    protected function configure()
    {
        $this->setDescription('Validation supervised object name');
    }


    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validationName($output);

        return 0;
    }

    /**
     * @param $output
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    protected function validationName($output)
    {
        $sql = array(
            'select id, name from structure.supervised_objects order by id ASC',
            'select id, name from structure.busines_entity order by id ASC'
        );
        foreach ($sql as $idx  => $value)
        {
            $conn = $this->defaultEntityManager->getConnection();

            $stmt = $conn->prepare($value);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $count = $stmt->rowCount();

            $counter = 1;

            $outputSection = $output->section();

            $updateSql = array(
                'UPDATE structure.supervised_objects SET name= :newNameObject WHERE id= :id',
                'UPDATE structure.busines_entity SET name= :newNameObject WHERE id= :id'
            );

            foreach ($result as $object)
            {
                try {
                    $outputSection->overwrite($this->getOutputString($count, $counter));
                    $id = $object['id'];

                    $strUpperName = mb_strtoupper($object['name']);

                    $newNameObject = '';

                    $checkList = array(
                        ['ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ', 'ООО '],
                        ['ОТКРЫТОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО', 'ОАО '],
                        ['ЗАКРЫТОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО', 'ЗАО '],
                        ['ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ', 'ИП '],
                        ['АКЦИОНЕРНОЕ ОБЩЕСТВО', 'АО '],
                        ['КРЕСТЬЯНСКОЕ (ФЕРМЕРСКОЕ) ХОЗЯЙСТВО', 'КФХ '],
                    );

                    foreach ($checkList as $option)
                    {
                        $subStr = mb_strcut($strUpperName, 0, strlen($option[0]) + 2);

                        $similar = similar_text($option[0], $subStr, $percent);

                        if ($percent > 95)
                        {
                            $length = strlen(mb_strcut($strUpperName, 0, $similar +1, 'UTF-8'));

                            $newNameObject = $this->str_replace_once($length, $option[1], $strUpperName);
                        }
                    }

                    if (!empty($newNameObject))
                    {

                            $conn = $this->defaultEntityManager->getConnection();

                            $stmt = $conn->prepare($updateSql[$idx]);

                            $stmt->execute([
                                'newNameObject' => $newNameObject,
                                'id' => $id
                            ]);
                    }

                    $counter ++;

                } catch (\Exception $exception) {
                    throw  $exception;
                }
            }

        }

        $output->writeln('Validation: SUCCESS');
    }

    private function str_replace_once(int $similary, string $replace, string $text): string
    {
        return  substr_replace($text, $replace, 0, $similary);
    }

}
