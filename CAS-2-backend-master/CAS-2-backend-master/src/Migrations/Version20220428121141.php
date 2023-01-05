<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428121141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE import.api_queue ADD supervised_object_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE import.api_queue ADD subdivision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE import.api_queue ADD CONSTRAINT FK_1F2271A9E3CB17DC FOREIGN KEY (supervised_object_id) REFERENCES structure.supervised_objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import.api_queue ADD CONSTRAINT FK_1F2271A9E05F13C FOREIGN KEY (subdivision_id) REFERENCES reference.subdivision (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1F2271A9E3CB17DC ON import.api_queue (supervised_object_id)');
        $this->addSql('CREATE INDEX IDX_1F2271A9E05F13C ON import.api_queue (subdivision_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE import.api_queue DROP CONSTRAINT FK_1F2271A9E3CB17DC');
        $this->addSql('ALTER TABLE import.api_queue DROP CONSTRAINT FK_1F2271A9E05F13C');
        $this->addSql('DROP INDEX IDX_1F2271A9E3CB17DC');
        $this->addSql('DROP INDEX IDX_1F2271A9E05F13C');
        $this->addSql('ALTER TABLE import.api_queue DROP supervised_object_id');
        $this->addSql('ALTER TABLE import.api_queue DROP subdivision_id');
    }
}
