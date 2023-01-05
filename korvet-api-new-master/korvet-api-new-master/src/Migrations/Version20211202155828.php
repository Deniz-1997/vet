<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202155828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET sort=53 WHERE name ='График работ';");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/pets', false, 0, false, false, false, 'Животные', 'pet', false, 'Пункт меню', (select parent_id from action.action where name='Все выезды'), 1004, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='pet'), 1);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/owners', false, 0, false, false, false, 'Владельцы', 'owner', false, 'Пункт меню', (select parent_id from action.action where name='Все выезды'), 1005, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE code='owner'), 1);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/appointments/create', false, 0, false, false, false, 'Добавить прием', 'CREATE_APPOINTMENT', false, null , null , 0, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/leaving/leaving-create', false, 0, false, false, false, 'Добавить выезд', 'CREATE_LEAVING', false, null , null , 0, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");

        $this->addSql("INSERT INTO action_action (action_source, action_target) VALUES ((select id from action.action where name='Все приемы'),(select id from action.action where code='CREATE_APPOINTMENT'));");
        $this->addSql("INSERT INTO action_action (action_source, action_target) VALUES ((select id from action.action where name='Все выезды'),(select id from action.action where code='CREATE_LEAVING'));");
        $this->addSql("INSERT INTO action_action (action_source, action_target) VALUES ((select id from action.action where code='owner'),(select id from action.action where code='CREATE_OWNER'));");
        $this->addSql("INSERT INTO action_action (action_source, action_target) VALUES ((select id from action.action where code='pet'),(select id from action.action where code='CREATE_PET'));");



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE code = 'pet');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'pet');");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE code = 'owner');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'owner');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'CREATE_APPOINTMENT');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'CREATE_LEAVING');");

    }
}
