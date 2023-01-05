<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022123207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("update structure.busines_entity set legal_forms = 'OOO' where legal_forms='ООО';");
        $this->addSql("update structure.busines_entity set legal_forms = 'IP' where legal_forms='ИП';");
        $this->addSql("update structure.busines_entity set legal_forms = 'AO' where legal_forms='АО';");
        $this->addSql("update structure.busines_entity set legal_forms = 'PAO' where legal_forms='ПАО';");
        $this->addSql("update structure.busines_entity set legal_forms = 'NKO' where legal_forms='НКО';");
        $this->addSql("update structure.busines_entity set legal_forms = 'OP' where legal_forms='ОП';");
        $this->addSql("update structure.busines_entity set legal_forms = 'ZAO' where legal_forms='ЗАО';");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
