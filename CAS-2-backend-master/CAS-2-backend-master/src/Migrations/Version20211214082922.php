<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214082922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reports.explanatory_note (id SERIAL NOT NULL, file_id INT DEFAULT NULL, user_id INT DEFAULT NULL, report_data_id INT NOT NULL, comment VARCHAR(2000) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_813D99CC93CB796C ON reports.explanatory_note (file_id)');
        $this->addSql('CREATE INDEX IDX_813D99CCA76ED395 ON reports.explanatory_note (user_id)');
        $this->addSql('CREATE INDEX IDX_813D99CC576CDC3C ON reports.explanatory_note (report_data_id)');
        $this->addSql('ALTER TABLE reports.explanatory_note ADD CONSTRAINT FK_813D99CC93CB796C FOREIGN KEY (file_id) REFERENCES uploaded_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reports.explanatory_note ADD CONSTRAINT FK_813D99CCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reports.explanatory_note ADD CONSTRAINT FK_813D99CC576CDC3C FOREIGN KEY (report_data_id) REFERENCES reports.reports_data (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE reports.explanatory_note');
    }
}
