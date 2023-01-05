<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001104317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET parent_id=(select id from action.action where name='Организации'), url='/reference/station' WHERE id=(select id from action.action where name='Станции')");
        $this->addSql("UPDATE action.action SET parent_id=(select id from action.action where name='Животные') WHERE id=(select id from action.action where name='Породы животных')");
        $this->addSql("UPDATE action.action SET parent_id=(select id from action.action where name='Животные') WHERE id=(select id from action.action where name='Бирки')");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/business-entity', false, 0, false, false, false, 'Хозяйственные субъекты', 'business-entity', false, 'Пункт меню', (select id from action.action where name='Организации'), 508, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/supervised-objects', false, 0, false, false, false, 'Поднадзорные объекты', 'supervised-objects', false, 'Пункт меню', (select id from action.action where name='Организации'), 508, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET parent_id=(select id from action.action where name='Справочники'), url='/organization/station' WHERE id=(select id from action.action where name='Станции')");
        $this->addSql("UPDATE action.action SET parent_id=(select id from action.action where name='Справочники') WHERE id=(select id from action.action where name='Породы животных')");
        $this->addSql("UPDATE action.action SET parent_id=(select id from action.action where name='Справочники') WHERE id=(select id from action.action where name='Бирки')");
        $this->addSql("DELETE FROM action.action WHERE id=(SELECT id FROM action.action WHERE code='business-entity');");
        $this->addSql("DELETE FROM action.action WHERE id=(SELECT id FROM action.action WHERE code='supervised-objects');");
    }
}
