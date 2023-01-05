<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923123734 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET Url='/vaccinations', name='Импорт вакцинаций', parent_id = (select id from action.action where code = 'reports'), description='Импорт вакцинаций' WHERE code='vaccination';");
        $this->addSql("UPDATE action.action SET sort=0 WHERE code='reports';");
        $this->addSql("UPDATE action.action SET description='Отчет 1ВЕТ-А' WHERE url='/reports/1-vet-a';");
        $this->addSql("UPDATE action.action SET description='Отчет дезинфицирующие средства' WHERE url='/reports/disinfectants';");
        $this->addSql("UPDATE action.action SET description='Отчет воспроизводство' WHERE url='/reports/reproduction';");
        $this->addSql("UPDATE action.action SET description='Отчет поголовье животных' WHERE code='report_livestock_of_animals';");
        $this->addSql("UPDATE action.action SET description='Отчет 3-Вет: Болезни рыб',  code = 'report_vet_3' WHERE name='3-Вет';");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

      
    }
}
