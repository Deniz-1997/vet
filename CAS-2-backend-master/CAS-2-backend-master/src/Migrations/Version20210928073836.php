<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928073836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE IF EXISTS structure.supervised_objects');
        $this->addSql('DROP TABLE IF EXISTS structure.busines_entity');

        $this->addSql('CREATE TABLE structure.busines_entity (id SERIAL NOT NULL, user_id INT DEFAULT NULL, legal_forms VARCHAR(255) NOT NULL, legal_addres VARCHAR(255) DEFAULT NULL, kpp VARCHAR(255) DEFAULT NULL, ogrn VARCHAR(255) DEFAULT NULL, inn VARCHAR(255) DEFAULT NULL, bik VARCHAR(255) DEFAULT NULL, head_full_name VARCHAR(255) DEFAULT NULL, head_office VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, registration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, liquidation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, plan_month INT DEFAULT NULL, plan_skip_year INT DEFAULT NULL, checking_account VARCHAR(255) DEFAULT NULL, cor_account VARCHAR(255) DEFAULT NULL, business_size VARCHAR(255) DEFAULT NULL, working_with_social_obj BOOLEAN DEFAULT \'false\', last_check INT DEFAULT NULL, risk_points INT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3CF4CAE9F75D7B0 ON structure.busines_entity (external_id)');
        $this->addSql('CREATE INDEX IDX_B3CF4CAEA76ED395 ON structure.busines_entity (user_id)');
        $this->addSql('COMMENT ON COLUMN structure.busines_entity.legal_forms IS \'(DC2Type:App\\Packages\\DBAL\\Types\\LegalFormsEnum)\'');
        $this->addSql('CREATE TABLE structure.supervised_objects (id SERIAL NOT NULL, user_id INT DEFAULT NULL, station_id INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, latitude INT DEFAULT NULL, longitude INT DEFAULT NULL, kpp VARCHAR(255) DEFAULT NULL, head_full_name VARCHAR(255) DEFAULT NULL, head_office VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, activity_kind VARCHAR(2000) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, telephone_number VARCHAR(255) DEFAULT NULL, internet_connection BOOLEAN DEFAULT \'false\', issues_certificates BOOLEAN DEFAULT \'false\', pushing_available BOOLEAN DEFAULT \'false\', compartment INT DEFAULT 0, name VARCHAR(255) DEFAULT \'\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, businessEntity_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8E47429F75D7B0 ON structure.supervised_objects (external_id)');
        $this->addSql('CREATE INDEX IDX_D8E4742A76ED395 ON structure.supervised_objects (user_id)');
        $this->addSql('CREATE INDEX IDX_D8E474221BDB235 ON structure.supervised_objects (station_id)');
        $this->addSql('CREATE INDEX IDX_D8E47422524B408 ON structure.supervised_objects (businessEntity_id)');
        $this->addSql('ALTER TABLE structure.busines_entity ADD CONSTRAINT FK_B3CF4CAEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE structure.supervised_objects ADD CONSTRAINT FK_D8E4742A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE structure.supervised_objects ADD CONSTRAINT FK_D8E474221BDB235 FOREIGN KEY (station_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE structure.supervised_objects ADD CONSTRAINT FK_D8E47422524B408 FOREIGN KEY (businessEntity_id) REFERENCES structure.busines_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.reference_station ADD external_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3385D289F75D7B0 ON reference.reference_station (external_id)');

        $this->addSql("update reference.reference_station set external_id = '0a889f9e-fac2-46ee-af5d-05d62f2367c9' where name like'%МособлВСС%' and external_id is null;");
        $this->addSql("update reference.reference_station set external_id = 'f8df98b2-79b8-48b8-b8f7-4d4ee4f71107' where name like'%Мособлветлаборатория%' and external_id is null;");
        $this->addSql("update reference.reference_station set external_id = '91f0025d-f57c-487a-8c8b-445c1c5c9d0e' where name like'%Министерство сельского хозяйства и продовольствия Московской области%' and external_id is null;");
        $this->addSql("update reference.reference_station set external_id = 'fbcdbc17-a309-4ce8-9269-3d6b6f049973' where name like'%Терветуправление № 1%' and external_id is null;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE structure.supervised_objects DROP CONSTRAINT FK_D8E47422524B408');
        $this->addSql('DROP TABLE structure.busines_entity');
        $this->addSql('DROP TABLE structure.supervised_objects');
        $this->addSql('ALTER TABLE reference.reference_station DROP external_id');
    }
}
