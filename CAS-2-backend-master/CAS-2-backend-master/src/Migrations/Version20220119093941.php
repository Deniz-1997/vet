<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119093941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE animal.animal ADD deleted BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE animal.animal_living_place ADD deleted BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE reference.path ALTER parent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reference.manufacturer ALTER country_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reference.circle ALTER location_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reference.circle ALTER radius TYPE INT');
        $this->addSql('ALTER TABLE reference.circle ALTER radius DROP DEFAULT');
        $this->addSql('ALTER TABLE animal.animal_stamps ALTER animal_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal.breed ALTER kind_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER kind_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER breed_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER colour_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER location_id DROP NOT NULL');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease DROP CONSTRAINT FK_83BE1BC2BFE75C3');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease ADD CONSTRAINT FK_83BE1BC2BFE75C3 FOREIGN KEY (vaccine_id) REFERENCES reference.vaccine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE animal.animal DROP deleted');
        $this->addSql('ALTER TABLE reference.path ALTER parent_id SET NOT NULL');
        $this->addSql('ALTER TABLE reference.manufacturer ALTER country_id SET NOT NULL');
        $this->addSql('ALTER TABLE reference.circle ALTER location_id SET NOT NULL');
        $this->addSql('ALTER TABLE reference.circle ALTER radius TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE reference.circle ALTER radius DROP DEFAULT');
        $this->addSql('ALTER TABLE animal.animal_living_place DROP deleted');
        $this->addSql('ALTER TABLE animal.animal ALTER kind_id SET NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER breed_id SET NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER colour_id SET NOT NULL');
        $this->addSql('ALTER TABLE animal.animal ALTER location_id SET NOT NULL');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease DROP CONSTRAINT fk_83be1bc2bfe75c3');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease ADD CONSTRAINT fk_83be1bc2bfe75c3 FOREIGN KEY (vaccine_id) REFERENCES reference.vaccine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
