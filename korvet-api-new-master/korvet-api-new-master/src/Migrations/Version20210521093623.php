<?php

declare(strict_types=1);

namespace DoctrineMigrations;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521093623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.leaving_status (id SERIAL NOT NULL, color VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT \'null\' NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reason_for_leaving (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO reference.leaving_status (id, name, color, code, deleted) VALUES (1, 'Выезд отменён', 'red', 'CANCELED', false)");
        $this->addSql("INSERT INTO reference.leaving_status (id, name, color, code, deleted) VALUES (2, 'Выезд запрланирован', 'blue', 'CREATED', false)");
        $this->addSql("INSERT INTO reference.leaving_status (id, name, color, code, deleted) VALUES (3, 'На выезде', 'green', 'OPENED', false)");
        $this->addSql("INSERT INTO reference.leaving_status (id, name, color, code, deleted) VALUES (4, 'Выезд завершен', 'red', 'COMPLETED', false)");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/leaving-status', true, 0, false, false, false, 'Статусы выезда', 'leaving-status', false, 'Статусы выезда', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Leaving\LeavingStatus', 'Статусы выезда');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Leaving\LeavingStatus'), 3);");
        $this->addSql("CREATE TRIGGER leaving_status_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.leaving_status FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/leaving-status');");

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reason-for-leaving', true, 0, false, false, false, 'Причина выезда', 'reason-for-leaving', false, 'Причина выезда', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Leaving\ReasonForLeaving', 'Причина выезда');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Leaving\ReasonForLeaving'), 3);");
        $this->addSql("CREATE TRIGGER reason_for_leaving_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reason_for_leaving FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reason-for-leaving');");
        $this->addSql("SELECT SETVAL('reference.reason_for_leaving_id_seq', (select max(id) from reference.reason_for_leaving));");
        $this->addSql("SELECT SETVAL('reference.leaving_status_id_seq', (select max(id) from reference.leaving_status));");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');

        $this->addSql('DROP TABLE reference.leaving_status');
        $this->addSql("DROP TRIGGER IF EXISTS leaving_status_items_count on reference.leaving_status ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Leaving\LeavingStatus');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Leaving\LeavingStatus');");
        $this->addSql('DROP TABLE reference.reason_for_leavinng');
        $this->addSql("DROP TRIGGER IF EXISTS reason_for_leaving_items_count on reference.reason_for_leaving ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Leaving\ReasonForLeaving');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Leaving\ReasonForLeaving');");


    }
}
