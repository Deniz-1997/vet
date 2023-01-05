<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608075441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        
        $this->addSql("INSERT INTO public.groups (id, name, code) values ((select max(id) + 1 from public.groups), 'Управляющие', 'ROLE_MANAGEMENT')");
        $this->addSql("INSERT INTO public.role_group (role_id, group_id) 
            values ((select id from public.roles where code = 'ROLE_MENU_PRINT_REPORTS'), (select id from public.groups where code='ROLE_MANAGEMENT'))");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DELETE FROM public.groups WHERE code = 'ROLE_MANAGEMENT'");
    }
}
