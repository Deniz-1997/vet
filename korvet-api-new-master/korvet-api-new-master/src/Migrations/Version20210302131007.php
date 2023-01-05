<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302131007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, 
        confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id,
         confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, 
         confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/notifications-channel', true, 0, false, false, false, 'Каналы оповещений', 'notifications-channel', false, 'Каналы по которым будут отсылаться оповещения', 
         NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Notifications\ReferenceNotificationsChannel', 'Канал оповещения');");

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, 
        confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id,
         confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, 
         confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/notifications-type', true, 0, false, false, false, 'Типы оповещений', 'notifications-type', false, 'Типы для оповещений', 
         NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Notifications\ReferenceNotificationsType', 'Тип оповещения');");

        $this->addSql('SELECT setval(\'action.action_id_seq\', (SELECT MAX(id) FROM action.action))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
