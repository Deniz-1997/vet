<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200115095001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {

        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_WRITE_FTP_HISTORY', 'История импорта/экспорта - Добавление + Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_READ_FTP_HISTORY', 'История импорта/экспорта - Чтение') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_ADD_FTP_HISTORY', 'История импорта/экспорта - Добавление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_UPDATE_FTP_HISTORY', 'История импорта/экспорта - Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_DELETE_FTP_HISTORY', 'История импорта/экспорта - Удаление') ON CONFLICT DO NOTHING");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
