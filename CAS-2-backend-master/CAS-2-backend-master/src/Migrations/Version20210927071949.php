<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927071949 extends AbstractMigration
{
    public function getDescription() : string
        {
            return 'Your description';
        }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/pet-common', false, 0, false, false, false, 'Животные (общий)', 'pets-common', false, 'Пункт меню', (select id from action.action where name='Животные'), 500, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/pet-living', false, 0, false, false, false, 'Место жительства', 'pets-living', false, 'Пункт меню', (select id from action.action where name='Животные'), 500, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/pet-stamp', false, 0, false, false, false, 'Чипы', 'pets-stamps', false, 'Пункт меню', (select id from action.action where name='Животные'), 500, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/pet-livestock', false, 0, false, false, false, 'Поголовье', 'pets-livestock', false, 'Пункт меню', (select id from action.action where name='Животные'), 500, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='pet'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='countries'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='disease'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='measurement-units'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='notification'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='organization'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='station'), 1);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='tag'), 1);");
    }

        public function down(Schema $schema) : void
        {
            $this->addSql("DELETE FROM action.action WHERE name = 'Животные (общий)';");
            $this->addSql("DELETE FROM action.action WHERE name = 'Место жительства';");
            $this->addSql("DELETE FROM action.action WHERE name = 'Чипы';");
            $this->addSql("DELETE FROM action.action WHERE name = 'Поголовье';");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='pet');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='countries');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='disease');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='measurement-units');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='notification');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='organization');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='station');");
            $this->addSql("DELETE FROM action_action_group WHERE (SELECT id FROM action.action WHERE code='tag');");
        }
}
