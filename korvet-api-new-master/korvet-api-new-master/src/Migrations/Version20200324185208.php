<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324185208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE form_field_property ADD default_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE form_template ADD appointment_count INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE form_template ADD parent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE form_field ADD description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE form_template DROP appointment_count');
        $this->addSql('ALTER TABLE form_template DROP parent');
        $this->addSql('ALTER TABLE form_field_property DROP default_value');
        $this->addSql('ALTER TABLE form_field DROP description');
    }
}
