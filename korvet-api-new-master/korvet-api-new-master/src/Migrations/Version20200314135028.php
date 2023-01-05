<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200314135028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE form_field (id SERIAL NOT NULL, field_template_id INT NOT NULL, form_template_id INT DEFAULT NULL, is_required BOOLEAN DEFAULT \'false\', sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8B2E19BC06DBC6F ON form_field (field_template_id)');
        $this->addSql('CREATE INDEX IDX_D8B2E19BF2B19FA9 ON form_field (form_template_id)');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19BC06DBC6F FOREIGN KEY (field_template_id) REFERENCES form_template_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19BF2B19FA9 FOREIGN KEY (form_template_id) REFERENCES form_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_template_field ALTER form_template_id DROP NOT NULL');
        $this->addSql('ALTER TABLE form_field_value DROP CONSTRAINT FK_C1BB5EDEF50D82F4');
        $this->addSql('ALTER TABLE form_field_value ADD CONSTRAINT FK_C1BB5EDEF50D82F4 FOREIGN KEY (form_field_id) REFERENCES form_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE form_field_value DROP CONSTRAINT FK_C1BB5EDEF50D82F4');
        $this->addSql('DROP TABLE form_field');
        $this->addSql('ALTER TABLE form_template_field ALTER form_template_id SET NOT NULL');
        $this->addSql('ALTER TABLE form_field_value DROP CONSTRAINT fk_c1bb5edef50d82f4');
        $this->addSql('ALTER TABLE form_field_value ADD CONSTRAINT fk_c1bb5edef50d82f4 FOREIGN KEY (form_field_id) REFERENCES form_template_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
