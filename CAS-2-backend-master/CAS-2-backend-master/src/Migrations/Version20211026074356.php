<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026074356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reports.reports RENAME COLUMN id TO uuid;');
        $this->addSql('ALTER TABLE reports.reports DROP CONSTRAINT reports_pkey CASCADE;');
        $this->addSql('ALTER TABLE reports.reports ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE reports.reports ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE reports.reports_data DROP CONSTRAINT reports_data_pkey CASCADE;');
        $this->addSql('ALTER TABLE reports.reports_data RENAME COLUMN reports_id TO reports_uuid;');
        $this->addSql('ALTER TABLE reports.reports_data DROP id');
        $this->addSql('ALTER TABLE reports.reports_data ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE reports.reports_data ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE reports.reports_data ADD reports_id INT');
        $this->addSql('ALTER TABLE reports.reports_data ADD CONSTRAINT FK_F9D12E617C5EAD31 FOREIGN KEY (reports_id) REFERENCES reports.reports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('UPDATE reports.reports_data r SET reports_id = (SELECT id FROM reports.reports WHERE uuid = r.reports_uuid LIMIT 1)');
        $this->addSql('ALTER TABLE reports.reports_data DROP reports_uuid');
        $this->addSql('ALTER TABLE reports.reports_data ALTER COLUMN reports_id SET NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
