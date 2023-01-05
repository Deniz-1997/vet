<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921105711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DELETE FROM action_role WHERE action_id = (SELECT id FROM action.action WHERE code = 'public_vaccinations');");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE code = 'public_vaccinations');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'public_vaccinations');");
        $this->addSql("DELETE FROM roles WHERE code = 'ROLE_MENU_PUBLIC_VACCINATION';");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) 
        VALUES ((select max(id)+1 from action.action), 'URL', '/public/vaccinations', false, 0, false, false, false, 'Реестр вакцинаций', 'public_vaccinations', false, 'Реестр вакцинаций', NULL, 0, NULL, NULL, '', '', '', '', 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO roles (id, parent_id, code, name, deleted, sort)
        VALUES ((select max(id)+1 from roles), NULL, 'ROLE_MENU_PUBLIC_VACCINATION', 'Пункт меню - Реестр вакцинаций', False, 0);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) 
        VALUES ((select (id) from action.action where code = 'public_vaccinations'), 1);");
        $this->addSql("INSERT INTO action_role (action_id, role_id) 
        VALUES ((select (id) from action.action where code = 'public_vaccinations'), (select (id) from roles where code = 'ROLE_MENU_PUBLIC_VACCINATION'));");
    }
}
