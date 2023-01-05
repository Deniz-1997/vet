<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520093947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE appointment_template_unit (appointment_template_id INT NOT NULL, unit_id INT NOT NULL, PRIMARY KEY(appointment_template_id, unit_id))');
        $this->addSql('CREATE INDEX IDX_773D98947E126D24 ON appointment_template_unit (appointment_template_id)');
        $this->addSql('CREATE INDEX IDX_773D9894F8BD700D ON appointment_template_unit (unit_id)');
        $this->addSql('ALTER TABLE appointment_template_unit ADD CONSTRAINT FK_773D98947E126D24 FOREIGN KEY (appointment_template_id) REFERENCES appointment.appointment_template (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_template_unit ADD CONSTRAINT FK_773D9894F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');



        $this->addSql('DROP TABLE appointment_template_unit');


    }
}
