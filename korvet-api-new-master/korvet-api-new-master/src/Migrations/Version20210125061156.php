<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125061156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS appointment');
        $this->addSql('ALTER TABLE appointment_form_template SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointment_index_search SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointment_logs SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointment_product_item SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointment_status SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointment_template SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointment_type SET SCHEMA appointment;');
        $this->addSql('ALTER TABLE appointments SET SCHEMA appointment;');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_state IS \'(DC2Type:App\\DBAL\\Types\\PaymentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_type IS \'(DC2Type:App\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_type IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE appointment_form_template SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointment_index_search SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointment_logs SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointment_product_item SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointment_status SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointment_template SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointment_type SET SCHEMA public;');
        $this->addSql('ALTER TABLE appointments SET SCHEMA public;');
        $this->addSql('DROP SCHEMA IF EXISTS appointment');
    }
}
