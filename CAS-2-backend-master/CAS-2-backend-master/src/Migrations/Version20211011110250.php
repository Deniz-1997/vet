<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211011110250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET url='/reference/pet-breed', code='pets-breed' WHERE id=(select id from action.action where name='Породы животных')");
        $this->addSql("UPDATE action.action SET url='/reference/pet-tag', code='pets-tag', deleted='true' WHERE id=(select id from action.action where name='Бирки')");
        $this->addSql("UPDATE action.action SET deleted='true' WHERE id=(select id from action.action where name='Поголовье')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET url='/dictionary/breed', code='Breed' WHERE id=(select id from action.action where name='Породы животных')");
        $this->addSql("UPDATE action.action SET url='/reference/tag', code='tag', deleted='false' WHERE id=(select id from action.action where name='Бирки')");
        $this->addSql("UPDATE action.action SET deleted='false' WHERE id=(select id from action.action where name='Поголовье')");
    }
}
