<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203135945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX animal.uniq_9a453d5c64d218e');
        $this->addSql('DROP INDEX reference.uniq_614c0522727aca70');
        $this->addSql('CREATE INDEX IDX_614C0522727ACA70 ON reference.location (parent_id)');



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE UNIQUE INDEX uniq_9a453d5c64d218e ON animal.animal (location_id)');
        $this->addSql('DROP INDEX IDX_614C0522727ACA70');
        $this->addSql('CREATE UNIQUE INDEX uniq_614c0522727aca70 ON reference.location (parent_id)');
    }
}
