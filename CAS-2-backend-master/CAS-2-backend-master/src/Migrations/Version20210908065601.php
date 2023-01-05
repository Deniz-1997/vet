<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210908065601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("insert into public.icon(class, name, code, id) values ('library_books', 'Справочники','references', (SELECT max(id)+1 FROM icon))");
        $this->addSql("insert into public.icon(class, name, code, id) values ('bloodtype', 'Вакцинации','bloodType', (SELECT max(id)+1 FROM icon) )");
        $this->addSql("update action.action set button_settings_icon_id=(select id from public.icon where class = 'library_books') where url = '/reference'");
        $this->addSql("update action.action set button_settings_icon_id=(select id from public.icon where class = 'bloodtype') where code = 'vaccination'");
        $this->addSql("update public.icon set class='settings' where code = 'administration'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
