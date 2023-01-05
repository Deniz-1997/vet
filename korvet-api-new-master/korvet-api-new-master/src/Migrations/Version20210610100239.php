<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610100239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('ALTER TABLE action.action_group ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE appointment.appointment_template ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE contractor_contact_persons ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE form.form_field_type ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE form.form_template ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE groups ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE organization ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reason_for_leaving ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_pet_aggressive_types ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_pet_lear ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_shelter ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_sterilization_type ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_stock ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_vaccination_type ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE reference.reference_veterinary_passport_type ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE roles ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE shop.shop_settings ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE template_reference ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE template_reference_value ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE icon ADD sort INT DEFAULT 0');
        $this->addSql('ALTER TABLE unit ADD sort INT DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE action.action_group DROP sort');
        $this->addSql('ALTER TABLE appointment.appointment_template DROP sort');
        $this->addSql('ALTER TABLE form.form_field_type DROP sort');
        $this->addSql('ALTER TABLE contractor_contact_persons DROP sort');
        $this->addSql('ALTER TABLE organization DROP sort');
        $this->addSql('ALTER TABLE template_reference DROP sort');
        $this->addSql('ALTER TABLE role_group DROP CONSTRAINT FK_9A1CACEAFE54D947');
        $this->addSql('ALTER TABLE roles DROP sort');
        $this->addSql('ALTER TABLE template_reference_value DROP sort');
        $this->addSql('ALTER TABLE reference.reference_pet_aggressive_types DROP sort');
        $this->addSql('ALTER TABLE reference.reference_pet_lear DROP sort');
        $this->addSql('ALTER TABLE shop.shop_settings DROP sort');
        $this->addSql('ALTER TABLE reference.reference_shelter DROP sort');
        $this->addSql('ALTER TABLE reference.reference_sterilization_type DROP sort');
        $this->addSql('ALTER TABLE reference.reference_stock DROP sort');
        $this->addSql('ALTER TABLE reference.reference_vaccination_type DROP sort');
        $this->addSql('ALTER TABLE reference.reference_veterinary_passport_type DROP sort');
        $this->addSql('ALTER TABLE groups DROP sort');
        $this->addSql('ALTER TABLE form.form_template DROP sort');
        $this->addSql('ALTER TABLE reference.reason_for_leaving DROP sort');

    }
}
