<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211014055116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id SET DEFAULT \'new\'');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id SET NOT NULL');
        $this->addSql("UPDATE reports.reports_data SET status_id = 'new'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE reports.reports_data SET status_id = '1'");
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id TYPE INT');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id SET DEFAULT 0');
        $this->addSql('ALTER TABLE reports.reports_data ALTER status_id DROP NOT NULL');
    }
}
