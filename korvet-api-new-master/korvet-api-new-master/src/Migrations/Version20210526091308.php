<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210526091308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA leaving');
        $this->addSql('CREATE SEQUENCE leaving.leaving_logs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE leaving.leaving_index_search (id SERIAL NOT NULL, leaving_id INT DEFAULT NULL, index TSVECTOR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FE814A99B544971 ON leaving.leaving_index_search (leaving_id)');
        $this->addSql('CREATE TABLE leaving.leaving_logs (id INT NOT NULL, user_id INT DEFAULT NULL, leaving_status_id INT DEFAULT NULL, leaving_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D14C626A76ED395 ON leaving.leaving_logs (user_id)');
        $this->addSql('CREATE INDEX IDX_5D14C6267169DD46 ON leaving.leaving_logs (leaving_status_id)');
        $this->addSql('CREATE INDEX IDX_5D14C6269B544971 ON leaving.leaving_logs (leaving_id)');
        $this->addSql('CREATE TABLE leaving.leaving_product_item (id SERIAL NOT NULL, leaving_id INT NOT NULL, creator_id INT DEFAULT NULL, product_id INT NOT NULL, stock_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, measure VARCHAR(255) DEFAULT NULL, price_with_charge DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_507537409B544971 ON leaving.leaving_product_item (leaving_id)');
        $this->addSql('CREATE INDEX IDX_5075374061220EA6 ON leaving.leaving_product_item (creator_id)');
        $this->addSql('CREATE INDEX IDX_507537404584665A ON leaving.leaving_product_item (product_id)');
        $this->addSql('CREATE INDEX IDX_50753740DCD6110 ON leaving.leaving_product_item (stock_id)');
        $this->addSql('CREATE INDEX IDX_50753740727ACA70 ON leaving.leaving_product_item (parent_id)');
        $this->addSql('CREATE TABLE leaving.leaving_type (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE leaving.leavings (id SERIAL NOT NULL, pet_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, user_id INT DEFAULT NULL, leaving_status_id INT DEFAULT NULL, reason_for_leaving_id INT DEFAULT NULL, profession_id INT DEFAULT NULL, previous_id INT DEFAULT NULL, unit_id INT DEFAULT NULL, cash_receipt_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, payment_state VARCHAR(255) DEFAULT \'NOT_PAID\' NOT NULL, type VARCHAR(255) DEFAULT NULL, name TEXT NOT NULL, diagnosis TEXT DEFAULT NULL, prescription TEXT DEFAULT NULL, survey TEXT DEFAULT NULL, is_extra_charge BOOLEAN DEFAULT \'false\', extra_charge DOUBLE PRECISION DEFAULT \'0\', payment_type VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT \'DRAFT\', uuid UUID DEFAULT NULL, errors TEXT DEFAULT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, weight_not_measured BOOLEAN DEFAULT \'false\' NOT NULL, temperature_not_measured BOOLEAN DEFAULT \'false\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, external_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAD41288D17F50A6 ON leaving.leavings (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAD412889F75D7B0 ON leaving.leavings (external_id)');
        $this->addSql('CREATE INDEX IDX_CAD41288966F7FB6 ON leaving.leavings (pet_id)');
        $this->addSql('CREATE INDEX IDX_CAD412887E3C61F9 ON leaving.leavings (owner_id)');
        $this->addSql('CREATE INDEX IDX_CAD41288A76ED395 ON leaving.leavings (user_id)');
        $this->addSql('CREATE INDEX IDX_CAD412887169DD46 ON leaving.leavings (leaving_status_id)');
        $this->addSql('CREATE INDEX IDX_CAD4128866C3295D ON leaving.leavings (reason_for_leaving_id)');
        $this->addSql('CREATE INDEX IDX_CAD41288FDEF8996 ON leaving.leavings (profession_id)');
        $this->addSql('CREATE INDEX IDX_CAD412882DE62210 ON leaving.leavings (previous_id)');
        $this->addSql('CREATE INDEX IDX_CAD41288F8BD700D ON leaving.leavings (unit_id)');
        $this->addSql('CREATE INDEX IDX_CAD412889FB2A19C ON leaving.leavings (cash_receipt_id)');
        $this->addSql('CREATE INDEX search_deleted ON leaving.leavings (deleted)');
        $this->addSql('COMMENT ON COLUMN leaving.leavings.payment_state IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN leaving.leavings.type IS \'(DC2Type:App\\Enum\\LeavingTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN leaving.leavings.payment_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN leaving.leavings.state IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN leaving.leavings.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN leaving.leavings.errors IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE leaving.leaving_index_search ADD CONSTRAINT FK_6FE814A99B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_logs ADD CONSTRAINT FK_5D14C626A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_logs ADD CONSTRAINT FK_5D14C6267169DD46 FOREIGN KEY (leaving_status_id) REFERENCES reference.leaving_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_logs ADD CONSTRAINT FK_5D14C6269B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_product_item ADD CONSTRAINT FK_507537409B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_product_item ADD CONSTRAINT FK_5075374061220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_product_item ADD CONSTRAINT FK_507537404584665A FOREIGN KEY (product_id) REFERENCES product.product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_product_item ADD CONSTRAINT FK_50753740DCD6110 FOREIGN KEY (stock_id) REFERENCES reference.reference_stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_product_item ADD CONSTRAINT FK_50753740727ACA70 FOREIGN KEY (parent_id) REFERENCES leaving.leaving_product_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD41288966F7FB6 FOREIGN KEY (pet_id) REFERENCES pet.pets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD412887E3C61F9 FOREIGN KEY (owner_id) REFERENCES owners (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD41288A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD412887169DD46 FOREIGN KEY (leaving_status_id) REFERENCES reference.leaving_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD4128866C3295D FOREIGN KEY (reason_for_leaving_id) REFERENCES reference.reason_for_leaving (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD41288FDEF8996 FOREIGN KEY (profession_id) REFERENCES reference.reference_profession (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD412882DE62210 FOREIGN KEY (previous_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD41288F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leavings ADD CONSTRAINT FK_CAD412889FB2A19C FOREIGN KEY (cash_receipt_id) REFERENCES cash.cash_receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action.action ADD CONSTRAINT FK_CFE36666727ACA70 FOREIGN KEY (parent_id) REFERENCES action.action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE files ADD leaving_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540599B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_63540599B544971 ON files (leaving_id)');
        $this->addSql('ALTER TABLE form.form_template ADD leaving_count INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE history_entity ALTER id DROP DEFAULT');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'NONE', '/leaving', false, 0, false, false, false, 'Выезд', 'leaving', false, 'Группа меню', NULL, 129, NULL, NULL, '', '', '', '', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE URL = '/leaving'), 1);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/leaving/leaving-list', false, 0, false, false, false, 'Все выезды', 'leaving-list', false, 'Раздел меню', (SELECT id FROM action.action WHERE name = 'Выезд'), 1001, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE URL = '/leaving/leaving-list'), 1);");
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/leaving/leaving-schedule', false, 0, false, false, false, 'График выездов', 'leaving-schedule', false, 'Раздел меню', (SELECT id FROM action.action WHERE name = 'Выезд'), 1003, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE URL = '/leaving/leaving-schedule'), 1);");
        $this->addSql("INSERT INTO icon (id, class, name, code, deleted) VALUES ((SELECT max(id)+1 FROM icon), 'icon-leaving', 'Выезд', 'leaving', false);");
        $this->addSql('ALTER TABLE appointment.appointment_form_template ADD leaving_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment.appointment_form_template ADD CONSTRAINT FK_746258FF9B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_746258FF9B544971 ON appointment.appointment_form_template (leaving_id)');
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_BUTTON_LEAVING_REGISTER', 'Кнопка - Зарегистрировать выезд') ON CONFLICT DO NOTHING");
        $this->addSql('alter table appointment.appointment_form_template alter column appointment_id drop not null;');

        $this->addSql('CREATE TABLE leaving.leaving_temperature (id SERIAL NOT NULL, temperature_id INT DEFAULT NULL, leaving_id INT DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF8172D0D9835775 ON leaving.leaving_temperature (temperature_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF8172D09B544971 ON leaving.leaving_temperature (leaving_id)');
        $this->addSql('CREATE TABLE leaving.leaving_weight (id SERIAL NOT NULL, weight_id INT DEFAULT NULL, leaving_id INT DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D21D69A6350035DC ON leaving.leaving_weight (weight_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D21D69A69B544971 ON leaving.leaving_weight (leaving_id)');
        $this->addSql('ALTER TABLE leaving.leaving_temperature ADD CONSTRAINT FK_EF8172D0D9835775 FOREIGN KEY (temperature_id) REFERENCES pet.pets_temperatures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_temperature ADD CONSTRAINT FK_EF8172D09B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_weight ADD CONSTRAINT FK_D21D69A6350035DC FOREIGN KEY (weight_id) REFERENCES pet.pets_weights (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leaving.leaving_weight ADD CONSTRAINT FK_D21D69A69B544971 FOREIGN KEY (leaving_id) REFERENCES leaving.leavings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO public.print_form (id, file_id, type, enabled, name, deleted, origin_file_name  ) VALUES ((select max(id)+1 from public.print_form), 2776, 'Leaving', true, 'Карточка вызова', false , 'Карточка приема вес.docx');");
        $this->addSql("INSERT INTO public.print_form (id, file_id, type, enabled, name, deleted, origin_file_name  ) VALUES ((select max(id)+1 from public.print_form), 2773, 'Leaving', true, 'Лист назначения', false , 'Лист назначения_вес.docx');");
        $this->addSql("INSERT INTO public.print_form (id, file_id, type, enabled, name, deleted, origin_file_name  ) VALUES ((select max(id)+1 from public.print_form), 2726, 'Leaving', true, 'Согласие на ветеринарное вмешательство', false , 'Согласие на ветеринарное вмешательство.docx');");
        $this->addSql("INSERT INTO public.print_form (id, file_id, type, enabled, name, deleted, origin_file_name  ) VALUES ((select max(id)+1 from public.print_form), 2734, 'Leaving', true, 'Информированное согласие на стационарное лечение', false , 'Информированное согласие на стационарное лечение.docx');");
        $this->addSql("INSERT INTO public.print_form (id, file_id, type, enabled, name, deleted, origin_file_name  ) VALUES ((select max(id)+1 from public.print_form), 1385, 'Owner+Leaving+Pet', true, 'Отказ от проведения анестезии при выполнении болезненных манипуляций', false , 'Отказ от проведения анестезии при выполнении болезненных манипуляций.docx');");
        $this->addSql("INSERT INTO public.print_form (id, file_id, type, enabled, name, deleted, origin_file_name  ) VALUES ((select max(id)+1 from public.print_form), 1383, 'Owner+Leaving+Pet', true, 'Добровольное согласие на вакцинацию', false , 'Информированное добровольное согласие владельца животного на вакцинацию.docx');");

        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_WRITE_LEAVING', 'Выезд - Добавление + Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_READ_LEAVING', 'Выезд - Чтение') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_ADD_LEAVING', 'Выезд - Добавление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_UPDATE_LEAVING', 'Выезд - Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_DELETE_LEAVING', 'Выезд - Удаление') ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_WRITE_LEAVING_TYPE', 'LeavingType - Добавление + Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_READ_LEAVING_TYPE', 'LeavingType - Чтение') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_ADD_LEAVING_TYPE', 'LeavingType - Добавление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_UPDATE_LEAVING_TYPE', 'LeavingType - Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_DELETE_LEAVING_TYPE', 'LeavingType - Удаление') ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_WRITE_LEAVING_STATUS', 'LeavingStatus - Добавление + Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_READ_LEAVING_STATUS', 'LeavingStatus - Чтение') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_ADD_LEAVING_STATUS', 'LeavingStatus - Добавление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_UPDATE_LEAVING_STATUS', 'LeavingStatus - Обновление') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_DELETE_LEAVING_STATUS', 'LeavingStatus - Удаление') ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_MENU_APPOINTMENT', 'Пункт меню - Выезд') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_MENU_APPOINTMENT_SCHEDULE', 'Пункт меню - График выездов') ON CONFLICT DO NOTHING");
        

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE leaving.leaving_product_item DROP CONSTRAINT FK_50753740727ACA70');
        $this->addSql('ALTER TABLE files DROP CONSTRAINT FK_63540599B544971');
        $this->addSql('ALTER TABLE leaving.leaving_index_search DROP CONSTRAINT FK_6FE814A99B544971');
        $this->addSql('ALTER TABLE leaving.leaving_logs DROP CONSTRAINT FK_5D14C6269B544971');
        $this->addSql('ALTER TABLE leaving.leaving_product_item DROP CONSTRAINT FK_507537409B544971');
        $this->addSql('ALTER TABLE leaving.leavings DROP CONSTRAINT FK_CAD412882DE62210');
        $this->addSql('DROP SEQUENCE leaving.leaving_logs_id_seq CASCADE');
        $this->addSql('DROP TABLE leaving.leaving_index_search');
        $this->addSql('DROP TABLE leaving.leaving_logs');
        $this->addSql('DROP TABLE leaving.leaving_product_item');
        $this->addSql('DROP TABLE leaving.leaving_type');
        $this->addSql('DROP TABLE leaving.leavings');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_type IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');
        $this->addSql('ALTER TABLE action.action DROP CONSTRAINT FK_CFE36666727ACA70');
        $this->addSql('DROP INDEX UNIQ_9B31A51C9B544971');
        $this->addSql('ALTER TABLE appointment.appointment_temperature DROP leaving_id');
        $this->addSql('DROP INDEX UNIQ_E7B0FDFF9B544971');
        $this->addSql('ALTER TABLE appointment.appointment_weight DROP leaving_id');
        $this->addSql('DROP INDEX IDX_62698E13A23B42D');
        $this->addSql('DROP INDEX IDX_62698E138183E7C2');
        $this->addSql('DROP INDEX IDX_62698E13C83688EA');
        $this->addSql('DROP INDEX IDX_62698E13AA756149');
        $this->addSql('CREATE SEQUENCE product.product_inventory_number_seq');
        $this->addSql('SELECT setval(\'product.product_inventory_number_seq\', (SELECT MAX(number) FROM product.product_inventory))');
        $this->addSql('ALTER TABLE product.product_inventory ALTER number SET DEFAULT nextval(\'product.product_inventory_number_seq\')');
        $this->addSql('DROP INDEX IDX_63540599B544971');
        $this->addSql('ALTER TABLE files DROP leaving_id');
        $this->addSql('ALTER TABLE form.form_template DROP leaving_count');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE url = '/leaving');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE url = '/leaving');");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE url = '/leaving/leaving-schedule');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE url = '/leaving/leaving-schedule');");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE url = '/leaving/leaving-list');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE url = '/leaving/leaving-list');");
        $this->addSql('ALTER TABLE appointment.appointment_form_template DROP CONSTRAINT FK_746258FF9B544971');
        $this->addSql('DROP INDEX IDX_746258FF9B544971');
        $this->addSql('ALTER TABLE appointment.appointment_form_template DROP leaving_id');


        $this->addSql('CREATE SCHEMA public');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_type IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');

        $this->addSql('DROP TABLE leaving.leaving_temperature');
        $this->addSql('DROP TABLE leaving.leaving_weight');
    }
}
