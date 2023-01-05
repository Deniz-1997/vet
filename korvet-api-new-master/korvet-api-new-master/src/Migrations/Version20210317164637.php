<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210317164637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE cash.cash_receipt_changes (id SERIAL NOT NULL, user_id INT NOT NULL, cash_receipt_id INT NOT NULL, type TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_69F15FD7A76ED395 ON cash.cash_receipt_changes (user_id)');
        $this->addSql('CREATE INDEX IDX_69F15FD79FB2A19C ON cash.cash_receipt_changes (cash_receipt_id)');
        $this->addSql('ALTER TABLE cash.cash_receipt_changes ADD CONSTRAINT FK_69F15FD7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cash.cash_receipt_changes ADD CONSTRAINT FK_69F15FD79FB2A19C FOREIGN KEY (cash_receipt_id) REFERENCES cash.cash_receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE cash.cash_receipt_changes');
    }
}
