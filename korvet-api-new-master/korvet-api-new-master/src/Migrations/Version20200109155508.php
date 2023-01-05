<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200109155508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE history_entity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE history_entity (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data JSON DEFAULT NULL, diff JSON DEFAULT NULL, comment TEXT DEFAULT NULL, user_user_id INT DEFAULT NULL, user_username VARCHAR(255) DEFAULT NULL, user_user_firstname VARCHAR(255) DEFAULT NULL, user_user_surname VARCHAR(255) DEFAULT NULL, user_user_patronymic VARCHAR(255) DEFAULT NULL, user_client_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX xaction ON history_entity (action)');
        $this->addSql('CREATE INDEX xloggedAt ON history_entity (logged_at)');
        $this->addSql('CREATE INDEX xobjectId ON history_entity (object_id)');
        $this->addSql('CREATE INDEX xobjectClass ON history_entity (object_class)');
        $this->addSql('CREATE INDEX xobjectMix ON history_entity (object_id, action, object_class)');
        $this->addSql('COMMENT ON COLUMN history_entity.data IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN history_entity.diff IS \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE history_entity_id_seq CASCADE');
        $this->addSql('DROP TABLE history_entity');
    }
}
