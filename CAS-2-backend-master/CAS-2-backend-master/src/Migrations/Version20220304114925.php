<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304114925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE import.api_queue (id SERIAL NOT NULL, business_entity_id INT DEFAULT NULL, station_id INT DEFAULT NULL, user_id INT NOT NULL, hash VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F2271A998417B22 ON import.api_queue (business_entity_id)');
        $this->addSql('CREATE INDEX IDX_1F2271A921BDB235 ON import.api_queue (station_id)');
        $this->addSql('CREATE INDEX IDX_1F2271A9A76ED395 ON import.api_queue (user_id)');
        $this->addSql('CREATE TABLE import.api_queue_row (id SERIAL NOT NULL, api_queue_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, hash VARCHAR(255) DEFAULT NULL, data JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86B1F01C9F75D7B0 ON import.api_queue_row (external_id)');
        $this->addSql('CREATE INDEX IDX_86B1F01C94409121 ON import.api_queue_row (api_queue_id)');
        $this->addSql('COMMENT ON COLUMN import.api_queue_row.status IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ApiQueueStatusEnum)\'');
        $this->addSql('ALTER TABLE import.api_queue ADD CONSTRAINT FK_1F2271A998417B22 FOREIGN KEY (business_entity_id) REFERENCES structure.busines_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.api_queue ADD CONSTRAINT FK_1F2271A921BDB235 FOREIGN KEY (station_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.api_queue ADD CONSTRAINT FK_1F2271A9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.api_queue_row ADD CONSTRAINT FK_86B1F01C94409121 FOREIGN KEY (api_queue_id) REFERENCES import.api_queue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE import.api_queue_row DROP CONSTRAINT FK_86B1F01C94409121');
        $this->addSql('DROP TABLE import.api_queue');
        $this->addSql('DROP TABLE import.api_queue_row');
    }
}
