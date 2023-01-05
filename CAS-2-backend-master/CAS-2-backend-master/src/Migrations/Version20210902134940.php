<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902134940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.reference_station (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE reference.reference_station ADD CONSTRAINT FK_C3385D28727ACA70 FOREIGN KEY (parent_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C3385D28727ACA70 ON reference.reference_station (parent_id)');
        $this->addSql('alter table reports.reports_data drop column station_id;');
        $this->addSql('CREATE  sequence IF NOT EXISTS reference.reference_station_id_seq');
        $this->addSql("alter table reference.reference_station alter column id set default nextval('reference.reference_station_id_seq'::regclass)");

    }


    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE reference.reference_station DROP CONSTRAINT FK_C3385D28727ACA70');
        $this->addSql('DROP TABLE reference.reference_station');
        $this->addSql('DROP INDEX IDX_F9D12E6121BDB235');


    }
}
