<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126100438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS action');
        $this->addSql('ALTER TABLE action SET SCHEMA action;');
        $this->addSql('ALTER TABLE action_action SET SCHEMA action;');
        $this->addSql('ALTER TABLE action_action_group SET SCHEMA action;');
        $this->addSql('ALTER TABLE action_group SET SCHEMA action;');
        $this->addSql('ALTER TABLE action_role SET SCHEMA action;');
        $this->addSql('COMMENT ON COLUMN action.action.type IS \'(DC2Type:App\\DBAL\\Types\\ActionTypeEnum)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE action.action SET SCHEMA public;');
        $this->addSql('ALTER TABLE action.action_action SET SCHEMA public;');
        $this->addSql('ALTER TABLE action.action_action_group SET SCHEMA public;');
        $this->addSql('ALTER TABLE action.action_group SET SCHEMA public;');
        $this->addSql('ALTER TABLE action.action_role SET SCHEMA public;');
        $this->addSql('DROP SCHEMA IF EXISTS action');
        $this->addSql('COMMENT ON COLUMN action.type IS \'(DC2Type:Webslon\\Bundle\\CMSBundle\\Doctrine\\DBAL\\Types\\ActionTypeEnum)\'');
    }
}
