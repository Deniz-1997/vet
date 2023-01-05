<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928142501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reports.reports_data ADD businessEntity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reports.reports_data ADD supervisedObjects_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reports.reports_data ADD CONSTRAINT FK_F9D12E612524B408 FOREIGN KEY (businessEntity_id) REFERENCES structure.busines_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reports.reports_data ADD CONSTRAINT FK_F9D12E6189C98A09 FOREIGN KEY (supervisedObjects_id) REFERENCES structure.supervised_objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F9D12E612524B408 ON reports.reports_data (businessEntity_id)');
        $this->addSql('CREATE INDEX IDX_F9D12E6189C98A09 ON reports.reports_data (supervisedObjects_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE reports.reports_data DROP CONSTRAINT FK_F9D12E612524B408');
        $this->addSql('ALTER TABLE reports.reports_data DROP CONSTRAINT FK_F9D12E6189C98A09');
        $this->addSql('DROP INDEX IDX_F9D12E612524B408');
        $this->addSql('DROP INDEX IDX_F9D12E6189C98A09');
        $this->addSql('ALTER TABLE reports.reports_data DROP businessEntity_id');
        $this->addSql('ALTER TABLE reports.reports_data DROP supervisedObjects_id');
    }
}
