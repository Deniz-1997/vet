<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128123400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS reference');
        $this->addSql('ALTER TABLE reference_animal_death SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_breeds SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_event_types SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_file_types SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_owner_activities SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_owner_files SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_owner_legal_forms SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_owner_status SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_pet_identifier_types SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_pet_lear SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_pet_types SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_profession SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_shelter SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_sterilization_type SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_stock SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_tag_colors SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_tag_forms SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_vaccination_type SET SCHEMA reference;');
        $this->addSql('ALTER TABLE reference_veterinary_passport_type SET SCHEMA reference;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reference.reference_animal_death SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_breeds SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_event_types SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_file_types SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_owner_activities SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_owner_files SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_owner_legal_forms SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_owner_status SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_pet_identifier_types SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_pet_lear SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_pet_types SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_profession SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_shelter SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_sterilization_type SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_stock SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_tag_colors SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_tag_forms SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_vaccination_type SET SCHEMA public;');
        $this->addSql('ALTER TABLE reference.reference_veterinary_passport_type SET SCHEMA public;');
        $this->addSql('DROP SCHEMA IF EXISTS reference');
    }
}
