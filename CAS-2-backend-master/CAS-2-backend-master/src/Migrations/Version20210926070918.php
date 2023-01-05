<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926070918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reports/1-vet-g', false, 0, false, false, false, '1-Вет Г', '1-vet-g', false, 'Отчет 1 Вет Г', (select id from action.action where name='Отчеты'), 900, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='1-vet-g'), 1);");
        $this->addSql("INSERT INTO reports.reports (id, uuid_tmp, name) values ('9209bcc5-1bd0-4a6a-bf4a-ea724b59d3d3', '9209bcc5-1bd0-4a6a-bf4a-ea724b59d3d3', '1-Вет Г');");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE code = '1-vet-g');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = '1-vet-g');");
        $this->addSql("DELETE FROM reports.reports WHERE name = '1-Вет Г';");
    }
}
