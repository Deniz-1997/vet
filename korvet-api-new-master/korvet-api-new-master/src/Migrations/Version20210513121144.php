<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210513121144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE laboratory.probe_item (id SERIAL NOT NULL, probe_id INT NOT NULL, packing_id INT NOT NULL, corrupt_reason_id INT DEFAULT NULL, probe_sampling_id INT DEFAULT NULL, code TEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, corrupted BOOLEAN DEFAULT NULL, corrupted_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_767B36E93D2D0D4A ON laboratory.probe_item (probe_id)');
        $this->addSql('CREATE INDEX IDX_767B36E9BF068368 ON laboratory.probe_item (packing_id)');
        $this->addSql('CREATE INDEX IDX_767B36E9C127B8B4 ON laboratory.probe_item (corrupt_reason_id)');
        $this->addSql('CREATE INDEX IDX_767B36E9F12C0616 ON laboratory.probe_item (probe_sampling_id)');
        $this->addSql('CREATE TABLE laboratory.probe_sampling (id SERIAL NOT NULL, pet_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, user_id INT DEFAULT NULL, appointment_id INT DEFAULT NULL, cash_receipt_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment TEXT DEFAULT NULL, payment_type VARCHAR(255) DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D9879AA966F7FB6 ON laboratory.probe_sampling (pet_id)');
        $this->addSql('CREATE INDEX IDX_6D9879AA7E3C61F9 ON laboratory.probe_sampling (owner_id)');
        $this->addSql('CREATE INDEX IDX_6D9879AAA76ED395 ON laboratory.probe_sampling (user_id)');
        $this->addSql('CREATE INDEX IDX_6D9879AAE5B533F9 ON laboratory.probe_sampling (appointment_id)');
        $this->addSql('CREATE INDEX IDX_6D9879AA9FB2A19C ON laboratory.probe_sampling (cash_receipt_id)');
        $this->addSql('COMMENT ON COLUMN laboratory.probe_sampling.payment_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('CREATE TABLE laboratory.research_document (number INT NOT NULL, research_reason_id INT DEFAULT NULL, research_priority_id INT DEFAULT NULL, probe_item_id INT DEFAULT NULL, research_id INT NOT NULL, research_equipment_id INT DEFAULT NULL, laboratory_id INT DEFAULT NULL, creator_id INT DEFAULT NULL, editor_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, status VARCHAR(255) DEFAULT \'CREATE\' NOT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, result TEXT DEFAULT NULL, state VARCHAR(255) DEFAULT \'DRAFT\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, errors TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(number))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_66391F88BF396750 ON laboratory.research_document (id)');
        $this->addSql('CREATE INDEX IDX_66391F8822F7C56B ON laboratory.research_document (research_reason_id)');
        $this->addSql('CREATE INDEX IDX_66391F88AD6EA95 ON laboratory.research_document (research_priority_id)');
        $this->addSql('CREATE INDEX IDX_66391F887E3E3B7F ON laboratory.research_document (probe_item_id)');
        $this->addSql('CREATE INDEX IDX_66391F887909E1ED ON laboratory.research_document (research_id)');
        $this->addSql('CREATE INDEX IDX_66391F881538697E ON laboratory.research_document (research_equipment_id)');
        $this->addSql('CREATE INDEX IDX_66391F882F2A371E ON laboratory.research_document (laboratory_id)');
        $this->addSql('CREATE INDEX IDX_66391F8861220EA6 ON laboratory.research_document (creator_id)');
        $this->addSql('CREATE INDEX IDX_66391F886995AC4C ON laboratory.research_document (editor_id)');
        $this->addSql('COMMENT ON COLUMN laboratory.research_document.status IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ResearchStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN laboratory.research_document.state IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN laboratory.research_document.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN laboratory.research_document.errors IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE product.research_document_document_product (research_document_number INT NOT NULL, document_product_id INT NOT NULL, PRIMARY KEY(research_document_number, document_product_id))');
        $this->addSql('CREATE INDEX IDX_66FC116A1AFCF98E ON product.research_document_document_product (research_document_number)');
        $this->addSql('CREATE INDEX IDX_66FC116AE447CD0C ON product.research_document_document_product (document_product_id)');
        $this->addSql('ALTER TABLE laboratory.probe_item ADD CONSTRAINT FK_767B36E93D2D0D4A FOREIGN KEY (probe_id) REFERENCES laboratory.probe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_item ADD CONSTRAINT FK_767B36E9BF068368 FOREIGN KEY (packing_id) REFERENCES laboratory.packing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_item ADD CONSTRAINT FK_767B36E9C127B8B4 FOREIGN KEY (corrupt_reason_id) REFERENCES laboratory.probe_corrupt_reason (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_item ADD CONSTRAINT FK_767B36E9F12C0616 FOREIGN KEY (probe_sampling_id) REFERENCES laboratory.probe_sampling (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_sampling ADD CONSTRAINT FK_6D9879AA966F7FB6 FOREIGN KEY (pet_id) REFERENCES pet.pets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_sampling ADD CONSTRAINT FK_6D9879AA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owners (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_sampling ADD CONSTRAINT FK_6D9879AAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_sampling ADD CONSTRAINT FK_6D9879AAE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment.appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.probe_sampling ADD CONSTRAINT FK_6D9879AA9FB2A19C FOREIGN KEY (cash_receipt_id) REFERENCES cash.cash_receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F8822F7C56B FOREIGN KEY (research_reason_id) REFERENCES laboratory.research_reason (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F88AD6EA95 FOREIGN KEY (research_priority_id) REFERENCES laboratory.research_priority (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F887E3E3B7F FOREIGN KEY (probe_item_id) REFERENCES laboratory.probe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F887909E1ED FOREIGN KEY (research_id) REFERENCES laboratory.research (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F881538697E FOREIGN KEY (research_equipment_id) REFERENCES laboratory.research_equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F882F2A371E FOREIGN KEY (laboratory_id) REFERENCES laboratory.laboratory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F8861220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE laboratory.research_document ADD CONSTRAINT FK_66391F886995AC4C FOREIGN KEY (editor_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product.research_document_document_product ADD CONSTRAINT FK_66FC116A1AFCF98E FOREIGN KEY (research_document_number) REFERENCES laboratory.research_document (number) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product.research_document_document_product ADD CONSTRAINT FK_66FC116AE447CD0C FOREIGN KEY (document_product_id) REFERENCES document_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'NONE', '/laboratory', false, 0, false, false, false, 'Лаборатория', 'laboratory_menu', false, 'Группа меню', NULL, 250, NULL, NULL, '', '', '', '', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE URL = '/laboratory'), 1);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/laboratory/probe-sampling', false, 0, false, false, false, 'Отбор проб', 'probe-sampling', false, 'Раздел меню', (SELECT id FROM action.action WHERE name = 'Лаборатория'), 100, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE URL = '/laboratory/probe-sampling'), 1);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/laboratory/research-document', false, 0, false, false, false, 'Исследования', 'research-document', false, 'Раздел меню', (SELECT id FROM action.action WHERE name = 'Лаборатория'), 150, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE URL = '/laboratory/research-document'), 1);");
        
        $this->addSql("INSERT INTO icon (id, class, name, code, deleted) VALUES ((SELECT max(id)+1 FROM icon), 'icon-laboratory', 'Лаборатория', 'laboratory', false);");

        $this->addSql('ALTER TABLE files ADD document_id TEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE product.research_document_document_product');
        $this->addSql('DROP TABLE laboratory.research_document');
        $this->addSql('DROP TABLE laboratory.probe_item');
        $this->addSql('DROP TABLE laboratory.probe_sampling');

        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE url = '/laboratory/probe-sampling');");
        $this->addSql("DELETE FROM action.action WHERE url = '/laboratory/probe-sampling';");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE url = '/laboratory/research-document');");
        $this->addSql("DELETE FROM action.action WHERE url = '/laboratory/research-document';");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE url = '/laboratory');");
        $this->addSql("DELETE FROM action.action WHERE url = '/laboratory';");

        $this->addSql("DELETE FROM icon WHERE code = 'laboratory';");

        $this->addSql('ALTER TABLE files DROP document_id');
    }
}
