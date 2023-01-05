<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211014130132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/common', false, 0, false, false, false, 'Общие', 'common', false, 'Пункт меню', (select id from action.action where name='Справочники'), 508, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("UPDATE action.action SET url='/reference/common-countries', parent_id=(SELECT id from action.action where code='common') WHERE id=(select id from action.action where name='Страны')");
        $this->addSql("UPDATE action.action SET url='/reference/common-measurement-units', parent_id=(SELECT id from action.action where code='common') WHERE id=(select id from action.action where name='Ед. измерения')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET url='/reference/reference-countries', parent_id=(SELECT id from action.action where code='reference') WHERE id=(select id from action.action where name='Страны')");
        $this->addSql("UPDATE action.action SET url='/reference/reference-measurement-units', parent_id=(SELECT id from action.action where code='reference') WHERE id=(select id from action.action where name='Ед. измерения')");
        $this->addSql("DELETE FROM action.action WHERE id=(SELECT id from action.action where code='common')");

    }
}
