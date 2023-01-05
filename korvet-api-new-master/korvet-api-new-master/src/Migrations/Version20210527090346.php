<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210527090346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE laboratory.research_history (id SERIAL NOT NULL, research_document_id UUID NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C3F9E3980928B5B ON laboratory.research_history (research_document_id)');
        $this->addSql('CREATE INDEX IDX_7C3F9E39A76ED395 ON laboratory.research_history (user_id)');
        $this->addSql('COMMENT ON COLUMN laboratory.research_history.research_document_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN laboratory.research_history.status IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ResearchStateEnum)\'');
        $this->addSql('ALTER TABLE laboratory.research_history ADD CONSTRAINT FK_7C3F9E3980928B5B FOREIGN KEY (research_document_id) REFERENCES laboratory.research_document (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_history ADD CONSTRAINT FK_7C3F9E39A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE laboratory.research_history');
    }
}
