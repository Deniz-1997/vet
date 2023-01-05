<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200306125941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE form_template_field (id SERIAL NOT NULL, form_template_id INT NOT NULL, field_type_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEE66DEDF2B19FA9 ON form_template_field (form_template_id)');
        $this->addSql('CREATE INDEX IDX_DEE66DED2B68A933 ON form_template_field (field_type_id)');
        $this->addSql('CREATE TABLE form_field_type (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, code VARCHAR(255) DEFAULT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D5720777153098 ON form_field_type (code)');
        $this->addSql('CREATE TABLE form_field_form_property (field_id INT NOT NULL, property_id INT NOT NULL, PRIMARY KEY(field_id, property_id))');
        $this->addSql('CREATE INDEX IDX_6127D99E443707B0 ON form_field_form_property (field_id)');
        $this->addSql('CREATE INDEX IDX_6127D99E549213EC ON form_field_form_property (property_id)');
        $this->addSql('CREATE TABLE form_field_property (id SERIAL NOT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB0F606977153098 ON form_field_property (code)');
        $this->addSql('CREATE TABLE form_template (id SERIAL NOT NULL, template TEXT DEFAULT NULL, fields_quantity INT DEFAULT 0 NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, active BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_265A9AC777153098 ON form_template (code)');
        $this->addSql('CREATE TABLE appointment_form_template (id SERIAL NOT NULL, appointment_id INT NOT NULL, form_template_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5812246DE5B533F9 ON appointment_form_template (appointment_id)');
        $this->addSql('CREATE INDEX IDX_5812246DF2B19FA9 ON appointment_form_template (form_template_id)');
        $this->addSql('CREATE TABLE form_field_property_value (id SERIAL NOT NULL, form_field_property_id INT DEFAULT NULL, form_field_id INT DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC52B260D4054342 ON form_field_property_value (form_field_property_id)');
        $this->addSql('CREATE INDEX IDX_DC52B260F50D82F4 ON form_field_property_value (form_field_id)');
        $this->addSql('CREATE TABLE form_field_value (id SERIAL NOT NULL, appointment_form_template_id INT DEFAULT NULL, form_field_id INT NOT NULL, value TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1BB5EDE241F0DF5 ON form_field_value (appointment_form_template_id)');
        $this->addSql('CREATE INDEX IDX_C1BB5EDEF50D82F4 ON form_field_value (form_field_id)');
        $this->addSql('ALTER TABLE form_template_field ADD CONSTRAINT FK_DEE66DEDF2B19FA9 FOREIGN KEY (form_template_id) REFERENCES form_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_template_field ADD CONSTRAINT FK_DEE66DED2B68A933 FOREIGN KEY (field_type_id) REFERENCES form_field_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field_form_property ADD CONSTRAINT FK_6127D99E443707B0 FOREIGN KEY (field_id) REFERENCES form_field_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field_form_property ADD CONSTRAINT FK_6127D99E549213EC FOREIGN KEY (property_id) REFERENCES form_field_property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_form_template ADD CONSTRAINT FK_5812246DE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_form_template ADD CONSTRAINT FK_5812246DF2B19FA9 FOREIGN KEY (form_template_id) REFERENCES form_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field_property_value ADD CONSTRAINT FK_DC52B260D4054342 FOREIGN KEY (form_field_property_id) REFERENCES form_field_property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field_property_value ADD CONSTRAINT FK_DC52B260F50D82F4 FOREIGN KEY (form_field_id) REFERENCES form_template_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field_value ADD CONSTRAINT FK_C1BB5EDE241F0DF5 FOREIGN KEY (appointment_form_template_id) REFERENCES appointment_form_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE form_field_value ADD CONSTRAINT FK_C1BB5EDEF50D82F4 FOREIGN KEY (form_field_id) REFERENCES form_template_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE form_field_property_value DROP CONSTRAINT FK_DC52B260F50D82F4');
        $this->addSql('ALTER TABLE form_field_value DROP CONSTRAINT FK_C1BB5EDEF50D82F4');
        $this->addSql('ALTER TABLE form_template_field DROP CONSTRAINT FK_DEE66DED2B68A933');
        $this->addSql('ALTER TABLE form_field_form_property DROP CONSTRAINT FK_6127D99E443707B0');
        $this->addSql('ALTER TABLE form_field_form_property DROP CONSTRAINT FK_6127D99E549213EC');
        $this->addSql('ALTER TABLE form_field_property_value DROP CONSTRAINT FK_DC52B260D4054342');
        $this->addSql('ALTER TABLE form_template_field DROP CONSTRAINT FK_DEE66DEDF2B19FA9');
        $this->addSql('ALTER TABLE appointment_form_template DROP CONSTRAINT FK_5812246DF2B19FA9');
        $this->addSql('ALTER TABLE form_field_value DROP CONSTRAINT FK_C1BB5EDE241F0DF5');
        $this->addSql('DROP TABLE form_template_field');
        $this->addSql('DROP TABLE form_field_type');
        $this->addSql('DROP TABLE form_field_form_property');
        $this->addSql('DROP TABLE form_field_property');
        $this->addSql('DROP TABLE form_template');
        $this->addSql('DROP TABLE appointment_form_template');
        $this->addSql('DROP TABLE form_field_property_value');
        $this->addSql('DROP TABLE form_field_value');
    }
}
