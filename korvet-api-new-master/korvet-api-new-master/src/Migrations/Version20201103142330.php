<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201103142330 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE unit ADD full_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD phone VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD website_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE unit DROP full_name');
        $this->addSql('ALTER TABLE unit DROP phone');
        $this->addSql('ALTER TABLE unit DROP email');
        $this->addSql('ALTER TABLE unit DROP website_url');
    }
}
