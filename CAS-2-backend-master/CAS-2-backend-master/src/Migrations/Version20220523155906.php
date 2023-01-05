<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220523155906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("DELETE FROM role_group WHERE group_id = (SELECT id FROM groups WHERE code = 'ROLE_ROOT') and role_id=(select (id) from roles where code = 'ROLE_MENU_VACCINATION');");
        $this->addSql("DELETE FROM role_group WHERE group_id = (SELECT id FROM groups WHERE code = 'ROLE_GOVERNMENT') and role_id=(select (id) from roles where code = 'ROLE_MENU_VACCINATION');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("INSERT INTO role_group (role_id, group_id) 
            VALUES ((select (id) from roles where code = 'ROLE_MENU_VACCINATION'), (select (id) from groups where code = 'ROLE_ROOT'));");
        $this->addSql("INSERT INTO role_group (role_id, group_id) 
            VALUES ((select (id) from roles where code = 'ROLE_MENU_VACCINATION'), (select (id) from groups where code = 'ROLE_GOVERNMENT'));");
    }
}
