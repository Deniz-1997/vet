<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202155999 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET sort = 1004 WHERE  code='owner'");
        $this->addSql("UPDATE action.action SET sort = 1005 WHERE  code='pet'");
        $this->addSql("UPDATE reference.leaving_status SET name = 'Выезд запланирован' WHERE  code='CREATED'");
    }

    public function down(Schema $schema) : void
    {


    }
}
