<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127101731 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS form');
        $this->addSql('ALTER TABLE form_field SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_field_form_property SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_field_property SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_field_property_value SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_field_type SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_field_value SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_template SET SCHEMA form;');
        $this->addSql('ALTER TABLE form_template_field SET SCHEMA form;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE form.form_field SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_field_form_property SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_field_property SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_field_property_value SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_field_type SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_field_value SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_template SET SCHEMA public;');
        $this->addSql('ALTER TABLE form.form_template_field SET SCHEMA public;');
        $this->addSql('DROP SCHEMA IF EXISTS form');
    }
}
