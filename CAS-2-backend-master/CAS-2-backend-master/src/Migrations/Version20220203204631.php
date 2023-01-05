<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203204631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE vaccination.vaccine_disease ADD CONSTRAINT FK_83BE1BC2BFE75C3 FOREIGN KEY (vaccine_id) REFERENCES reference.vaccine (id) DEFERRABLE ');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease ADD CONSTRAINT FK_83BE1BCD8355341 FOREIGN KEY (disease_id) REFERENCES reference.reference_disease (id) DEFERRABLE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE vaccination.vaccine_disease DROP CONSTRAINT FK_83BE1BC2BFE75C3');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease DROP CONSTRAINT FK_83BE1BCD8355341');
    }
}
