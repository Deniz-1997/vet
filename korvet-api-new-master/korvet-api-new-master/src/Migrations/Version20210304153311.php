<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304153311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE IF EXISTS appointment_status_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS appointment.appointment_status_id_seq');
        $this->addSql('SELECT setval(\'appointment.appointment_status_id_seq\', (SELECT MAX(id) FROM appointment.appointment_status))');
        $this->addSql('ALTER TABLE appointment.appointment_status ALTER id SET DEFAULT nextval(\'appointment.appointment_status_id_seq\')');
        //$this->addSql('ALTER TABLE appointment.appointment_status ADD code VARCHAR(255) DEFAULT \'null\' NOT NULL');
        $this->addSql("UPDATE  appointment.appointment_status  SET code='CREATED' WHERE name='Запланирован'");
        $this->addSql("UPDATE  appointment.appointment_status  SET code='OPENED' WHERE name='Проводится'");
        $this->addSql("UPDATE  appointment.appointment_status  SET code='CANCELED' WHERE name='Завершен'");
        $this->addSql("UPDATE  appointment.appointment_status  SET code='CANCELED' WHERE name='Отменен'");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE appointment.appointment_status DROP code');
    }
}
