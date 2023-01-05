<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511065949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA laboratory');
        $this->addSql('CREATE TABLE laboratory.laboratory (id SERIAL NOT NULL, stock_id INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, coordinates VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, external BOOLEAN DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_89EDFAD6DCD6110 ON laboratory.laboratory (stock_id)');
        $this->addSql('ALTER TABLE laboratory.laboratory ADD CONSTRAINT FK_89EDFAD6DCD6110 FOREIGN KEY (stock_id) REFERENCES reference.reference_stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/laboratory', true, 0, false, false, false, 'Лаборатории', 'laboratory', false, 'Лаборатории', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\Laboratory', 'Лаборатории');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Laboratory'), 3);");
        $this->addSql("CREATE TRIGGER laboratory_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.laboratory FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/laboratory');");
        
        $this->addSql('CREATE TABLE laboratory.material_type (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/material-type', true, 0, false, false, false, 'Тип материала', 'material-type', false, 'Тип материала', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\MaterialType', 'Тип материала');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\MaterialType'), 3);");
        $this->addSql("CREATE TRIGGER material_type_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.material_type FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/material-type');");
        
        $this->addSql('CREATE TABLE laboratory.packing (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/packing', true, 0, false, false, false, 'Упаковка', 'packing', false, 'Упаковка', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\Packing', 'Упаковка');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Packing'), 3);");
        $this->addSql("CREATE TRIGGER packing_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.packing FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/packing');");
        
        $this->addSql('CREATE TABLE laboratory.research_priority (id SERIAL NOT NULL, level INT NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/research-priority', true, 0, false, false, false, 'Приоритет исследования', 'research-priority', false, 'Приоритет исследования', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\ResearchPriority', 'Приоритет исследования');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchPriority'), 3);");
        $this->addSql("CREATE TRIGGER research_priority_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.research_priority FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/research-priority');");
        
        $this->addSql('CREATE TABLE laboratory.research_reason (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/research-reason', true, 0, false, false, false, 'Причина исследования', 'research-reason', false, 'Причина исследования', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\ResearchReason', 'Причина исследования');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchReason'), 3);");
        $this->addSql("CREATE TRIGGER research_reason_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.research_reason FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/research-reason');");

        $this->addSql('CREATE TABLE laboratory.probe (id SERIAL NOT NULL, packing_id INT DEFAULT NULL, material_type_id INT NOT NULL, vat_rate VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, active BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_53210D9EBF068368 ON laboratory.probe (packing_id)');
        $this->addSql('CREATE INDEX IDX_53210D9E74D6573C ON laboratory.probe (material_type_id)');
        $this->addSql('ALTER TABLE laboratory.probe ADD CONSTRAINT FK_53210D9EBF068368 FOREIGN KEY (packing_id) REFERENCES laboratory.packing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe ADD CONSTRAINT FK_53210D9E74D6573C FOREIGN KEY (material_type_id) REFERENCES laboratory.material_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('COMMENT ON COLUMN laboratory.probe.vat_rate IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/probe', true, 0, false, false, false, 'Проба', 'probe', false, 'Проба', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\Probe', 'Проба');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Probe'), 3);");
        $this->addSql("CREATE TRIGGER probe_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.probe FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/probe');");

        $this->addSql('CREATE TABLE laboratory.research (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/research', true, 0, false, false, false, 'Исследование', 'research', false, 'Исследование', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\Research', 'Исследование');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Research'), 3);");
        $this->addSql("CREATE TRIGGER research_reason_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.research FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/research');");

        $this->addSql('CREATE TABLE laboratory.probe_corrupt_reason (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/probe-corrupt-reason', true, 0, false, false, false, 'Причина брака пробы', 'probe-corrupt-reason', false, 'Причина брака пробы', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\ProbeCorruptReason', 'Причина брака пробы');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ProbeCorruptReason'), 3);");
        $this->addSql("CREATE TRIGGER probe_corrupt_reason_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.probe_corrupt_reason FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/probe-corrupt-reason');");

        $this->addSql('CREATE TABLE laboratory.research_equipment (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/laboratory/research-equipment', true, 0, false, false, false, 'Оборудование для исследования', 'research-equipment', false, 'Оборудование для исследования', NULL, (select max(sort)+50 from action.action), NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Laboratory\ResearchEquipment', 'Оборудование для исследования');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchEquipment'), 3);");
        $this->addSql("CREATE TRIGGER research_equipment_items_count AFTER INSERT OR DELETE OR UPDATE ON laboratory.research_equipment FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/laboratory/research-equipment');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE laboratory.probe');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Probe');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Probe');");
        $this->addSql("DROP TRIGGER IF EXISTS probe_items_count on laboratory.probe");

        $this->addSql('DROP TABLE laboratory.laboratory');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Laboratory');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Laboratory');");
        $this->addSql("DROP TRIGGER IF EXISTS laboratory_items_count on laboratory.laboratory");

        $this->addSql('DROP TABLE laboratory.material_type');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\MaterialType');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\MaterialType');");
        $this->addSql("DROP TRIGGER IF EXISTS material_type_items_count on laboratory.material_type");

        $this->addSql('DROP TABLE laboratory.packing');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Packing');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Packing');");
        $this->addSql("DROP TRIGGER IF EXISTS packing_items_count on laboratory.packing");

        $this->addSql('DROP TABLE laboratory.research_priority');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchPriority');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchPriority');");
        $this->addSql("DROP TRIGGER IF EXISTS research_priority_items_count on laboratory.research_priority");

        $this->addSql('DROP TABLE laboratory.research_reason');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchReason');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchReason');");
        $this->addSql("DROP TRIGGER IF EXISTS research_reason_items_count on laboratory.research_reason");

        $this->addSql('DROP TABLE laboratory.research');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Research');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\Research');");
        $this->addSql("DROP TRIGGER IF EXISTS research_reason_items_count on laboratory.research");

        $this->addSql('DROP TABLE laboratory.probe_corrupt_reason');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ProbeCorruptReason');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ProbeCorruptReason');");
        $this->addSql("DROP TRIGGER IF EXISTS probe_corrupt_reason_items_count on laboratory.probe_corrupt_reason");

        $this->addSql('DROP TABLE laboratory.research_equipment');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchEquipment');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Laboratory\ResearchEquipment');");
        $this->addSql("DROP TRIGGER IF EXISTS research_equipment_items_count on laboratory.research_equipment");

        $this->addSql('DROP SCHEMA IF EXISTS laboratory');
    }
}
