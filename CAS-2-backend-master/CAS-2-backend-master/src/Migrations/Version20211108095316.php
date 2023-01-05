<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108095316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE busines_entity_user (busines_entity_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(busines_entity_id, user_id))');
        $this->addSql('CREATE INDEX IDX_7B8C01C8F2B4D5F5 ON busines_entity_user (busines_entity_id)');
        $this->addSql('CREATE INDEX IDX_7B8C01C8A76ED395 ON busines_entity_user (user_id)');
        $this->addSql('ALTER TABLE busines_entity_user ADD CONSTRAINT FK_7B8C01C8F2B4D5F5 FOREIGN KEY (busines_entity_id) REFERENCES structure.busines_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE busines_entity_user ADD CONSTRAINT FK_7B8C01C8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO busines_entity_user SELECT id, user_id FROM structure.busines_entity where user_id is not null');
        $this->addSql('ALTER TABLE structure.busines_entity DROP CONSTRAINT fk_b3cf4caea76ed395');
        $this->addSql('ALTER TABLE structure.busines_entity DROP user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE structure.busines_entity ADD IF NOT EXISTS user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure.busines_entity ADD CONSTRAINT fk_b3cf4caea76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_b3cf4caea76ed395 ON structure.busines_entity (user_id)');
        $this->addSql('UPDATE structure.busines_entity SET user_id = u.user_id FROM busines_entity_user AS u WHERE id = u.busines_entity_id');
        $this->addSql('DROP TABLE busines_entity_user');
    }
}
