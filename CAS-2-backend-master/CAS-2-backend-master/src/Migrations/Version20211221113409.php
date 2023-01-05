<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221113409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS animal');
        $this->addSql('CREATE SCHEMA IF NOT EXISTS import');
        $this->addSql('CREATE SCHEMA IF NOT EXISTS vaccination');
        $this->addSql('DROP SEQUENCE IF EXISTS print_form_history_id_seq CASCADE');
        $this->addSql('CREATE TABLE animal.animal (id SERIAL NOT NULL, kind_id INT NOT NULL, breed_id INT NOT NULL, colour_id INT NOT NULL, location_id INT NOT NULL, birthdate DATE NOT NULL, owner VARCHAR(255) DEFAULT NULL, chip VARCHAR(15) DEFAULT NULL, gender VARCHAR(255) DEFAULT \'male\' NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9A453D5C9F75D7B0 ON animal.animal (external_id)');
        $this->addSql('CREATE INDEX IDX_9A453D5C30602CA9 ON animal.animal (kind_id)');
        $this->addSql('CREATE INDEX IDX_9A453D5CA8B4A30F ON animal.animal (breed_id)');
        $this->addSql('CREATE INDEX IDX_9A453D5C569C9B4C ON animal.animal (colour_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9A453D5C64D218E ON animal.animal (location_id)');
        $this->addSql('COMMENT ON COLUMN animal.animal.gender IS \'(DC2Type:App\\Packages\\DBAL\\Types\\AnimalGenderEnum)\'');
        $this->addSql('CREATE TABLE animal.animal_living_place (id SERIAL NOT NULL, country_id INT DEFAULT NULL, location_id INT DEFAULT NULL, arrival_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address VARCHAR(255) NOT NULL, is_current BOOLEAN NOT NULL, note VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_806F252F9F75D7B0 ON animal.animal_living_place (external_id)');
        $this->addSql('CREATE INDEX IDX_806F252FF92F3E70 ON animal.animal_living_place (country_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_806F252F64D218E ON animal.animal_living_place (location_id)');
        $this->addSql('CREATE TABLE animal.animal_stamps (id SERIAL NOT NULL, animal_id INT NOT NULL, stamp_date DATE DEFAULT NULL, is_current BOOLEAN NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F2CCAF6C9F75D7B0 ON animal.animal_stamps (external_id)');
        $this->addSql('CREATE INDEX IDX_F2CCAF6C8E962C16 ON animal.animal_stamps (animal_id)');
        $this->addSql('CREATE TABLE animal.breed (id SERIAL NOT NULL, kind_id INT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_19228FDF9F75D7B0 ON animal.breed (external_id)');
        $this->addSql('CREATE INDEX IDX_19228FDF30602CA9 ON animal.breed (kind_id)');
        $this->addSql('CREATE TABLE reference.circle (id SERIAL NOT NULL, location_id INT NOT NULL, group_num INT DEFAULT NULL, build_order INT DEFAULT NULL, included BOOLEAN DEFAULT NULL, center VARCHAR(255) NOT NULL, radius DOUBLE PRECISION NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_83C90FE29F75D7B0 ON reference.circle (external_id)');
        $this->addSql('CREATE INDEX IDX_83C90FE264D218E ON reference.circle (location_id)');
        $this->addSql('CREATE TABLE animal.colour (id SERIAL NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A167B8D9F75D7B0 ON animal.colour (external_id)');
        $this->addSql('CREATE TABLE animal.kind (id SERIAL NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B85B34929F75D7B0 ON animal.kind (external_id)');
        $this->addSql('CREATE TABLE reference.location (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, fias_id UUID DEFAULT NULL, region_fias_id UUID DEFAULT NULL, area_fias_id UUID DEFAULT NULL, city_fias_id UUID DEFAULT NULL, city_district_fias_id UUID DEFAULT NULL, settlement_fias_id UUID DEFAULT NULL, street_fias_id UUID DEFAULT NULL, house_fias_id UUID DEFAULT NULL, center VARCHAR(255) DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_614C05229F75D7B0 ON reference.location (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_614C0522727ACA70 ON reference.location (parent_id)');
        $this->addSql('CREATE TABLE reference.manufacturer (id SERIAL NOT NULL, country_id INT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B3A802A9F75D7B0 ON reference.manufacturer (external_id)');
        $this->addSql('CREATE INDEX IDX_7B3A802AF92F3E70 ON reference.manufacturer (country_id)');
        $this->addSql('CREATE TABLE reference.path (id SERIAL NOT NULL, parent_id INT NOT NULL, group_num INT DEFAULT NULL, build_order INT DEFAULT NULL, included BOOLEAN DEFAULT NULL, data VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B825BAD09F75D7B0 ON reference.path (external_id)');
        $this->addSql('CREATE INDEX IDX_B825BAD0727ACA70 ON reference.path (parent_id)');
        $this->addSql('CREATE TABLE import.uploaded_vaccination_excel_file (id SERIAL NOT NULL, fixed_id INT DEFAULT NULL, station_id INT NOT NULL, user_id INT DEFAULT NULL, hash VARCHAR(32) NOT NULL, status VARCHAR(255) DEFAULT \'uploaded\' NOT NULL, status_msg VARCHAR(500) DEFAULT NULL, source_name VARCHAR(500) NOT NULL, lock TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, response_hash VARCHAR(32) DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90E229B0D1B862B8 ON import.uploaded_vaccination_excel_file (hash)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90E229B07D196822 ON import.uploaded_vaccination_excel_file (response_hash)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90E229B09F75D7B0 ON import.uploaded_vaccination_excel_file (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90E229B0F66F7484 ON import.uploaded_vaccination_excel_file (fixed_id)');
        $this->addSql('CREATE INDEX IDX_90E229B021BDB235 ON import.uploaded_vaccination_excel_file (station_id)');
        $this->addSql('CREATE INDEX IDX_90E229B0A76ED395 ON import.uploaded_vaccination_excel_file (user_id)');
        $this->addSql('COMMENT ON COLUMN import.uploaded_vaccination_excel_file.status IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VaccinationUploadStatusEnum)\'');
        $this->addSql('CREATE TABLE import.uploaded_vaccination_excel_row (id SERIAL NOT NULL, vaccination_id INT DEFAULT NULL, excel_file_id INT NOT NULL, status VARCHAR(255) DEFAULT \'uploaded\' NOT NULL, parsed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, processed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, row_number INT NOT NULL, data TEXT NOT NULL, status_msg VARCHAR(500) DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_954E348A9F75D7B0 ON import.uploaded_vaccination_excel_row (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_954E348A4DDCCFA3 ON import.uploaded_vaccination_excel_row (vaccination_id)');
        $this->addSql('CREATE INDEX IDX_954E348AF8E0D200 ON import.uploaded_vaccination_excel_row (excel_file_id)');
        $this->addSql('COMMENT ON COLUMN import.uploaded_vaccination_excel_row.status IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VaccinationUploadStatusEnum)\'');
        $this->addSql('CREATE TABLE reference.vaccination (id SERIAL NOT NULL, created_by INT DEFAULT NULL, station INT DEFAULT NULL, date DATE NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42D116369F75D7B0 ON reference.vaccination (external_id)');
        $this->addSql('CREATE INDEX IDX_42D11636DE12AB56 ON reference.vaccination (created_by)');
        $this->addSql('CREATE INDEX IDX_42D116369F39F8B1 ON reference.vaccination (station)');
        $this->addSql('CREATE TABLE vaccination.vaccination_animal (vaccination_id INT NOT NULL, animal_id INT NOT NULL, PRIMARY KEY(vaccination_id, animal_id))');
        $this->addSql('CREATE INDEX IDX_A4D229BA4DDCCFA3 ON vaccination.vaccination_animal (vaccination_id)');
        $this->addSql('CREATE INDEX IDX_A4D229BA8E962C16 ON vaccination.vaccination_animal (animal_id)');
        $this->addSql('CREATE TABLE vaccination.vaccination_vaccine_series (vaccination_id INT NOT NULL, vaccine_series_id INT NOT NULL, PRIMARY KEY(vaccination_id, vaccine_series_id))');
        $this->addSql('CREATE INDEX IDX_C54B92B84DDCCFA3 ON vaccination.vaccination_vaccine_series (vaccination_id)');
        $this->addSql('CREATE INDEX IDX_C54B92B8AB51BC95 ON vaccination.vaccination_vaccine_series (vaccine_series_id)');
        $this->addSql('CREATE TABLE vaccination.vaccination_person (vaccination_id INT NOT NULL, person_id INT NOT NULL, PRIMARY KEY(vaccination_id, person_id))');
        $this->addSql('CREATE INDEX IDX_FAA5DBD34DDCCFA3 ON vaccination.vaccination_person (vaccination_id)');
        $this->addSql('CREATE INDEX IDX_FAA5DBD3217BBB47 ON vaccination.vaccination_person (person_id)');
        $this->addSql('CREATE TABLE reference.vaccine (id SERIAL NOT NULL, manufacturer_id INT DEFAULT NULL, activity_duration INT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C057A4179F75D7B0 ON reference.vaccine (external_id)');
        $this->addSql('CREATE INDEX IDX_C057A417A23B42D ON reference.vaccine (manufacturer_id)');
        $this->addSql('CREATE TABLE vaccination.vaccine_disease (vaccine_id INT NOT NULL, disease_id INT NOT NULL, PRIMARY KEY(vaccine_id, disease_id))');
        $this->addSql('CREATE INDEX IDX_83BE1BC2BFE75C3 ON vaccination.vaccine_disease (vaccine_id)');
        $this->addSql('CREATE INDEX IDX_83BE1BCD8355341 ON vaccination.vaccine_disease (disease_id)');
        $this->addSql('CREATE TABLE reference.vaccine_series (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, vaccine_id INT NOT NULL, serial_number VARCHAR(255) NOT NULL, produced TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, external_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FA9EACB9F75D7B0 ON reference.vaccine_series (external_id)');
        $this->addSql('CREATE INDEX IDX_1FA9EACB727ACA70 ON reference.vaccine_series (parent_id)');
        $this->addSql('CREATE INDEX IDX_1FA9EACB2BFE75C3 ON reference.vaccine_series (vaccine_id)');
        $this->addSql('ALTER TABLE animal.animal ADD CONSTRAINT FK_9A453D5C30602CA9 FOREIGN KEY (kind_id) REFERENCES animal.kind (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.animal ADD CONSTRAINT FK_9A453D5CA8B4A30F FOREIGN KEY (breed_id) REFERENCES animal.breed (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.animal ADD CONSTRAINT FK_9A453D5C569C9B4C FOREIGN KEY (colour_id) REFERENCES animal.colour (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.animal ADD CONSTRAINT FK_9A453D5C64D218E FOREIGN KEY (location_id) REFERENCES reference.location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.animal_living_place ADD CONSTRAINT FK_806F252FF92F3E70 FOREIGN KEY (country_id) REFERENCES reference.reference_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.animal_living_place ADD CONSTRAINT FK_806F252F64D218E FOREIGN KEY (location_id) REFERENCES reference.location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.animal_stamps ADD CONSTRAINT FK_F2CCAF6C8E962C16 FOREIGN KEY (animal_id) REFERENCES animal.animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal.breed ADD CONSTRAINT FK_19228FDF30602CA9 FOREIGN KEY (kind_id) REFERENCES animal.kind (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.circle ADD CONSTRAINT FK_83C90FE264D218E FOREIGN KEY (location_id) REFERENCES reference.location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.location ADD CONSTRAINT FK_614C0522727ACA70 FOREIGN KEY (parent_id) REFERENCES reference.location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.manufacturer ADD CONSTRAINT FK_7B3A802AF92F3E70 FOREIGN KEY (country_id) REFERENCES reference.reference_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.path ADD CONSTRAINT FK_B825BAD0727ACA70 FOREIGN KEY (parent_id) REFERENCES reference.location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_file ADD CONSTRAINT FK_90E229B0F66F7484 FOREIGN KEY (fixed_id) REFERENCES import.uploaded_vaccination_excel_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_file ADD CONSTRAINT FK_90E229B021BDB235 FOREIGN KEY (station_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_file ADD CONSTRAINT FK_90E229B0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_row ADD CONSTRAINT FK_954E348A4DDCCFA3 FOREIGN KEY (vaccination_id) REFERENCES reference.vaccination (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_row ADD CONSTRAINT FK_954E348AF8E0D200 FOREIGN KEY (excel_file_id) REFERENCES import.uploaded_vaccination_excel_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.vaccination ADD CONSTRAINT FK_42D11636DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.vaccination ADD CONSTRAINT FK_42D116369F39F8B1 FOREIGN KEY (station) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccination_animal ADD CONSTRAINT FK_A4D229BA4DDCCFA3 FOREIGN KEY (vaccination_id) REFERENCES reference.vaccination (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccination_animal ADD CONSTRAINT FK_A4D229BA8E962C16 FOREIGN KEY (animal_id) REFERENCES animal.animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccination_vaccine_series ADD CONSTRAINT FK_C54B92B84DDCCFA3 FOREIGN KEY (vaccination_id) REFERENCES reference.vaccination (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccination_vaccine_series ADD CONSTRAINT FK_C54B92B8AB51BC95 FOREIGN KEY (vaccine_series_id) REFERENCES reference.vaccine_series (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccination_person ADD CONSTRAINT FK_FAA5DBD34DDCCFA3 FOREIGN KEY (vaccination_id) REFERENCES reference.vaccination (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccination_person ADD CONSTRAINT FK_FAA5DBD3217BBB47 FOREIGN KEY (person_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.vaccine ADD CONSTRAINT FK_C057A417A23B42D FOREIGN KEY (manufacturer_id) REFERENCES reference.manufacturer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease ADD CONSTRAINT FK_83BE1BC2BFE75C3 FOREIGN KEY (vaccine_id) REFERENCES reference.vaccine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease ADD CONSTRAINT FK_83BE1BCD8355341 FOREIGN KEY (disease_id) REFERENCES reference.reference_disease (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.vaccine_series ADD CONSTRAINT FK_1FA9EACB727ACA70 FOREIGN KEY (parent_id) REFERENCES reference.vaccine_series (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reference.vaccine_series ADD CONSTRAINT FK_1FA9EACB2BFE75C3 FOREIGN KEY (vaccine_id) REFERENCES reference.vaccine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE print_form_history');
        $this->addSql('ALTER TABLE reference.reference_countries ADD iso CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE reference.reference_countries ADD external_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reference.reference_countries ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE reference.reference_countries ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN reference.reference_countries.iso IS \'Цифровой ISO-код\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5B59D1989F75D7B0 ON reference.reference_countries (external_id)');
        $this->addSql('ALTER TABLE reference.reference_disease ADD external_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_533ACF589F75D7B0 ON reference.reference_disease (external_id)');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id SET DEFAULT \'new\'');
        $this->addSql('ALTER TABLE reports.reports_data ALTER data SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN reports.reports_data.status_id IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ReportStatusEnum)\'');
        $this->addSql('COMMENT ON COLUMN reports.reports_data.data IS NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE animal.animal_stamps DROP CONSTRAINT FK_F2CCAF6C8E962C16');
        $this->addSql('ALTER TABLE vaccination.vaccination_animal DROP CONSTRAINT FK_A4D229BA8E962C16');
        $this->addSql('ALTER TABLE animal.animal DROP CONSTRAINT FK_9A453D5CA8B4A30F');
        $this->addSql('ALTER TABLE animal.animal DROP CONSTRAINT FK_9A453D5C569C9B4C');
        $this->addSql('ALTER TABLE animal.animal DROP CONSTRAINT FK_9A453D5C30602CA9');
        $this->addSql('ALTER TABLE animal.breed DROP CONSTRAINT FK_19228FDF30602CA9');
        $this->addSql('ALTER TABLE animal.animal DROP CONSTRAINT FK_9A453D5C64D218E');
        $this->addSql('ALTER TABLE animal.animal_living_place DROP CONSTRAINT FK_806F252F64D218E');
        $this->addSql('ALTER TABLE reference.circle DROP CONSTRAINT FK_83C90FE264D218E');
        $this->addSql('ALTER TABLE reference.location DROP CONSTRAINT FK_614C0522727ACA70');
        $this->addSql('ALTER TABLE reference.path DROP CONSTRAINT FK_B825BAD0727ACA70');
        $this->addSql('ALTER TABLE reference.vaccine DROP CONSTRAINT FK_C057A417A23B42D');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_file DROP CONSTRAINT FK_90E229B0F66F7484');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_row DROP CONSTRAINT FK_954E348AF8E0D200');
        $this->addSql('ALTER TABLE import.uploaded_vaccination_excel_row DROP CONSTRAINT FK_954E348A4DDCCFA3');
        $this->addSql('ALTER TABLE vaccination.vaccination_animal DROP CONSTRAINT FK_A4D229BA4DDCCFA3');
        $this->addSql('ALTER TABLE vaccination.vaccination_vaccine_series DROP CONSTRAINT FK_C54B92B84DDCCFA3');
        $this->addSql('ALTER TABLE vaccination.vaccination_person DROP CONSTRAINT FK_FAA5DBD34DDCCFA3');
        $this->addSql('ALTER TABLE vaccination.vaccine_disease DROP CONSTRAINT FK_83BE1BC2BFE75C3');
        $this->addSql('ALTER TABLE reference.vaccine_series DROP CONSTRAINT FK_1FA9EACB2BFE75C3');
        $this->addSql('ALTER TABLE vaccination.vaccination_vaccine_series DROP CONSTRAINT FK_C54B92B8AB51BC95');
        $this->addSql('ALTER TABLE reference.vaccine_series DROP CONSTRAINT FK_1FA9EACB727ACA70');
        $this->addSql('CREATE SEQUENCE print_form_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE print_form_history (id SERIAL NOT NULL, user_id INT DEFAULT NULL, print_form TEXT NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d1eb9da9a76ed395 ON print_form_history (user_id)');
        $this->addSql('ALTER TABLE print_form_history ADD CONSTRAINT fk_d1eb9da9a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE animal.animal');
        $this->addSql('DROP TABLE animal.animal_living_place');
        $this->addSql('DROP TABLE animal.animal_stamps');
        $this->addSql('DROP TABLE animal.breed');
        $this->addSql('DROP TABLE reference.circle');
        $this->addSql('DROP TABLE animal.colour');
        $this->addSql('DROP TABLE animal.kind');
        $this->addSql('DROP TABLE reference.location');
        $this->addSql('DROP TABLE reference.manufacturer');
        $this->addSql('DROP TABLE reference.path');
        $this->addSql('DROP TABLE import.uploaded_vaccination_excel_file');
        $this->addSql('DROP TABLE import.uploaded_vaccination_excel_row');
        $this->addSql('DROP TABLE reference.vaccination');
        $this->addSql('DROP TABLE vaccination.vaccination_animal');
        $this->addSql('DROP TABLE vaccination.vaccination_vaccine_series');
        $this->addSql('DROP TABLE vaccination.vaccination_person');
        $this->addSql('DROP TABLE reference.vaccine');
        $this->addSql('DROP TABLE vaccination.vaccine_disease');
        $this->addSql('DROP TABLE reference.vaccine_series');
        $this->addSql('ALTER TABLE reference.reference_disease DROP external_id');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id SET DEFAULT \'new\'');
        $this->addSql('ALTER TABLE reports.reports_data ALTER data DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN reports.reports_data.status_id IS NULL');
        $this->addSql('COMMENT ON COLUMN reports.reports_data.data IS \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE reference.reference_countries DROP iso');
        $this->addSql('ALTER TABLE reference.reference_countries DROP external_id');
        $this->addSql('ALTER TABLE reference.reference_countries DROP created_at');
        $this->addSql('ALTER TABLE reference.reference_countries DROP updated_at');
    }
}
