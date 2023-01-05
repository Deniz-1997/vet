<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209084547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) 
            VALUES ((select max(id)+1 from action.action), 'NONE', '/print-reports', false, 0, false, false, false, 'Печатные отчеты', 'print-reports', false, 'Пункт меню', NULL, 1000, NULL, NULL, '', '', '', '', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='print-reports'), 1);");

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) 
            VALUES ((select max(id)+1 from action.action), 'URL', '/print-reports/users-business-entity', false, 0, false, false, false, 'Пользователи хозяйствующих субъектов', 'users-business-entity', false, 'Пункт меню', (select id from action.action where code='print-reports'), 100, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='users-business-entity'), 1);");

        $this->addSql("INSERT INTO roles (id, parent_id, code, name, deleted, sort)
            VALUES ((select max(id)+1 from roles), NULL, 'ROLE_MENU_PRINT_REPORTS', 'Пункт меню - Печатные отчеты', False, 0);");

        $this->addSql("INSERT INTO action_role (action_id, role_id) 
            VALUES ((select (id) from action.action where code = 'print-reports'), (select (id) from roles where code = 'ROLE_MENU_PRINT_REPORTS'));");

        $this->addSql("INSERT INTO role_group (role_id, group_id) 
            VALUES ((select (id) from roles where code = 'ROLE_MENU_PRINT_REPORTS'), (select (id) from groups where code = 'ROLE_ROOT'));");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM action_role WHERE action_id = (SELECT id FROM action.action WHERE code = 'print-reports');");
        $this->addSql("DELETE FROM role_group WHERE role_id = (SELECT id FROM roles WHERE code = 'ROLE_MENU_PRINT_REPORTS');");
        $this->addSql("DELETE FROM roles WHERE code = 'ROLE_MENU_PRINT_REPORTS';");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE code = 'users-business-entity');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'users-business-entity');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'print-reports');");
    }
}
