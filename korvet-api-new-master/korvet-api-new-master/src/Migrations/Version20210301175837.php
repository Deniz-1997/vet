<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301175837 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
       
        $this->addSql('DROP TABLE IF EXISTS appointment.appointment_temperature');
        $this->addSql('DROP TABLE IF EXISTS appointment.appointment_weight');

        $this->addSql('CREATE TABLE appointment.appointment_temperature (id SERIAL NOT NULL, temperature_id INT NOT NULL, appointment_id INT NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B31A51CD9835775 ON appointment.appointment_temperature (temperature_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B31A51CE5B533F9 ON appointment.appointment_temperature (appointment_id)');
        $this->addSql('CREATE TABLE appointment.appointment_weight (id SERIAL NOT NULL, weight_id INT NOT NULL, appointment_id INT NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7B0FDFF350035DC ON appointment.appointment_weight (weight_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7B0FDFFE5B533F9 ON appointment.appointment_weight (appointment_id)');
        $this->addSql('ALTER TABLE appointment.appointment_temperature ADD CONSTRAINT FK_9B31A51CD9835775 FOREIGN KEY (temperature_id) REFERENCES pet.pets_temperatures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment.appointment_temperature ADD CONSTRAINT FK_9B31A51CE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment.appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment.appointment_weight ADD CONSTRAINT FK_E7B0FDFF350035DC FOREIGN KEY (weight_id) REFERENCES pet.pets_weights (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment.appointment_weight ADD CONSTRAINT FK_E7B0FDFFE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment.appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment.appointments ADD weight_not_measured BOOLEAN DEFAULT \'false\'');
        $this->addSql('ALTER TABLE appointment.appointments ADD temperature_not_measured BOOLEAN DEFAULT \'false\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE appointment.appointment_temperature');
        $this->addSql('DROP TABLE appointment.appointment_weight');
        $this->addSql('ALTER TABLE appointment.appointments DROP weight_not_measured');
        $this->addSql('ALTER TABLE appointment.appointments DROP temperature_not_measured');
    }
}
