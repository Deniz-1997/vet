<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831115318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('CREATE SCHEMA reports');
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql('CREATE TABLE reports.reports (id uuid not null default uuid_generate_v4(), uuid_tmp UUID NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reports.reports_data (id uuid not null default uuid_generate_v4(), reports_id uuid not null default uuid_generate_v4(), station_id UUID NOT NULL, status_id INT DEFAULT 0, year INT NOT NULL, quarter INT DEFAULT NULL, month INT DEFAULT NULL, data JSONB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F9D12E617C5EAD31 ON reports.reports_data (reports_id)');
        $this->addSql('COMMENT ON COLUMN reports.reports_data.data IS \'(DC2Type:json_array)\'');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reports.reports_data DROP CONSTRAINT FK_F9D12E617C5EAD31');
        $this->addSql('DROP TABLE reports.reports');
        $this->addSql('DROP TABLE reports.reports_data');
        $this->addSql('ALTER TABLE users ALTER salt DROP NOT NULL');
    }
}
