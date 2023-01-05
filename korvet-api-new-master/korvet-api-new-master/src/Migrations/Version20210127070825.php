<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127070825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS pet');
        $this->addSql('ALTER TABLE pets SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet_index_search SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet_to_owner SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pets_identifiers SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pets_identifiers_history SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pets_temperatures SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pets_weights SET SCHEMA pet;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE pet.pets SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet.pet_index_search SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet.pet_to_owner SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet.pets_identifiers SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet.pets_identifiers_history SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet.pets_temperatures SET SCHEMA pet;');
        $this->addSql('ALTER TABLE pet.pets_weights SET SCHEMA pet;');
        $this->addSql('DROP SCHEMA IF EXISTS pet');
    }
}
