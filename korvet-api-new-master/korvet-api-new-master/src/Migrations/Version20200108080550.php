<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Reference\Breed;
use App\Entity\Reference\PetType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200108080550 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ContainerInterface */
    private $container;


    const DOG_LEAR_LIST = ['Черный', 'Кофейный', 'Красный', 'Махагоновый', 'Полевой', 'Абрикосовый', 'Голубой', 'Серебристого',
        'Зонарно-серый', 'Кабаний', 'Агути', 'Зонарно-рыжий', 'Соболиный', 'Олений', 'Муругий', 'Чепрачный', 'Чубарый',
        'Тигровый', 'Коричнево-подпалый', 'Тёмная маска', 'Обратная маска', 'Ирландская пятнистость', 'Коричнево-пегий',
        'Крап', 'Далматин', 'Блю-мерль', 'Ред-мерль', 'Мраморный', 'Подпалый в сочетании с пегостью', 'Чепрачный в сочетании с пегостью',
        'Тигровый в сочетании с пегостью', 'Тигровый в сочетании с ирландской пятнистостью', 'Тигровый с экстремальной пятнистостью',
        'Чёрно-подпалый блю-мерль с ирландской пятнистостью', 'Чёрно-подпалый крапчатый, пегий', 'Чёрно-подпалый крапчато-чалый с экстремальной пятнистостью',
        'Тигрово-подпалый', 'Блю-мерль с белым', 'Пегий с возрастным осветлением', 'Пего-чалый', 'Соболиный с обратной маской',
        'Красно-зонарный с обратной маской', 'Тёмная маска на фоне обратной маски'];

    const CAT_LEAR_LIST = ['Голубой', 'Шоколадный', 'Гавана', 'Каштановый', 'Коричневый', 'Шампань', 'Лиловый', 'Лавандовый', 'Платина',
        'Красный (рыжий)', 'Кремовый', 'Черная черепаха', 'Голубая черепаха (голубокремовый)', 'Шоколадная черепаха', 'Лиловая черепаха',
        'Карамельный', 'Черный', 'Угольный', 'Циннамон (коричный)', 'Медовый', 'Соррель', 'Фавн (олененок)', 'Беж', 'Желто-коричневый',
        'Циннамон-торти', 'Фавн-торти', 'Дымный', 'Серебряный', 'Амбер (янтарь)', 'Белый', 'Золотистый', 'Ван', 'Арлекин', 'Биколор',
        'Колор-пойнт биколор', 'Сноушу', 'Маленькие белые пятна', 'Затушёванный', 'Агути', 'Табби', 'Мраморный', 'Тигровый', 'Пятнистый',
        'Абиссинский табби', 'Сепия', 'Минк', 'Сиамский', 'Сингапурский'];

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
    }

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        /**
         * @var PetType[] $dogs
         */
        $dogs = $this->entityManager->getRepository(PetType::class)->findBy(['name' => 'Собака']);
        /**
         * @var PetType[] $cats
         */
        $cats = $this->entityManager->getRepository(PetType::class)->findBy(['name' => 'Кошка']);

        foreach ($dogs as $dog) {
            /**
             * @var Breed[] $dogBreeds
             */
            $dogBreeds = $this->entityManager->getRepository(Breed::class)->findBy(['type' => $dog->getId()]);
            foreach ($dogBreeds as $dogBreed) {
                $breedId = $dogBreed->getId();
                foreach (self::DOG_LEAR_LIST as $dogLear) {
                    $this->addSql("INSERT INTO reference_pet_lear (breed_id, name) VALUES ('$breedId', '$dogLear') ON CONFLICT DO NOTHING");
                }
            }
        }
        foreach ($cats as $cat) {
            /**
             * @var Breed[] $catBreeds
             */
            $catBreeds = $this->entityManager->getRepository(Breed::class)->findBy(['type' => $cat->getId()]);
            foreach ($catBreeds as $catBreed) {
                $breedId = $catBreed->getId();
                foreach (self::CAT_LEAR_LIST as $catLear) {
                    $this->addSql("INSERT INTO reference_pet_lear (breed_id, name) VALUES ('$breedId', '$catLear') ON CONFLICT DO NOTHING");
                }
            }
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $dogLearList = '\'';
        foreach (self::DOG_LEAR_LIST as $dogLear) {
            $dogLearList .= $dogLear.'\', \'';
        }
        $dogLearList = substr($dogLearList, 0, -3);
        $catLearList = '\'';
        foreach (self::CAT_LEAR_LIST as $catLear) {
            $catLearList .= $catLear.'\', \'';
        }
        $catLearList = substr($catLearList, 0, -3);
        $this->addSql("DELETE FROM reference_pet_lear WHERE name IN($dogLearList)");
        $this->addSql("DELETE FROM reference_pet_lear WHERE name IN($catLearList)");

    }
}
