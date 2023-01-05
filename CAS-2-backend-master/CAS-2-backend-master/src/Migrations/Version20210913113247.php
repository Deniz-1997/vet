<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913113247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reports/1-vet-a', false, 0, false, false, false, '1-Вет А', '1-vet-a', false, 'Пункт меню', (select id from action.action where name='Отчеты'), 600, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='1-vet-a'), 1);");
        $this->addSql("INSERT INTO reports.reports (id, uuid_tmp, name) values ('4b3db081-909e-4fba-a604-06c8bc8934be', '4b3db081-909e-4fba-a604-06c8bc8934be', '1-Вет А');");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE code = '1-vet-a');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = '1-vet-a');");
        $this->addSql("DELETE FROM reports.reports WHERE name = '1-Вет А';");
    }
}
