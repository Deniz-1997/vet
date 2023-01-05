<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210214163657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX IF EXISTS uniq_54ac335be5b533f9');
        $this->addSql('ALTER TABLE reference.reference_pet_aggressive_types ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE unit ALTER without_registry DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reference.reference_pet_aggressive_types ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE unit ALTER without_registry SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_54ac335be5b533f9 ON appointment.appointment_index_search (appointment_id)');
    }
}
