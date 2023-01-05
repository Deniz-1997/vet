<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806101824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA action');
        $this->addSql('CREATE SCHEMA notifications');
        $this->addSql('CREATE SCHEMA reference');
        $this->addSql('CREATE SEQUENCE history_entity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notifications.notifications_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notifications.notifications_to_send_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reference.reference_notifications_channel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reference.reference_notifications_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE action.action (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, items_count_enabled BOOLEAN NOT NULL, items_count INT DEFAULT NULL, get_list_enabled BOOLEAN NOT NULL, view_item_enabled BOOLEAN NOT NULL, get_item_enabled BOOLEAN NOT NULL, description TEXT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, code VARCHAR(255) DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, button_settings_color VARCHAR(255) DEFAULT NULL, button_settings_background_color VARCHAR(255) DEFAULT NULL, button_settings_icon_id INT DEFAULT NULL, confirmation_title VARCHAR(255) DEFAULT NULL, confirmation_description TEXT DEFAULT NULL, confirmation_icon_id INT DEFAULT NULL, confirmation_confirm_button_color VARCHAR(255) DEFAULT NULL, confirmation_confirm_button_background_color VARCHAR(255) DEFAULT NULL, confirmation_confirm_button_icon_id INT DEFAULT NULL, confirmation_cancel_button_color VARCHAR(255) DEFAULT NULL, confirmation_cancel_button_background_color VARCHAR(255) DEFAULT NULL, confirmation_cancel_button_icon_id INT DEFAULT NULL, entity_name VARCHAR(255) DEFAULT NULL, entity_class_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFE3666677153098 ON action.action (code)');
        $this->addSql('CREATE INDEX IDX_CFE36666727ACA70 ON action.action (parent_id)');
        $this->addSql('COMMENT ON COLUMN action.action.type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ActionTypeEnum)\'');
        $this->addSql('CREATE TABLE action_action (action_source INT NOT NULL, action_target INT NOT NULL, PRIMARY KEY(action_source, action_target))');
        $this->addSql('CREATE INDEX IDX_85FCBDF99DBA4E18 ON action_action (action_source)');
        $this->addSql('CREATE INDEX IDX_85FCBDF9845F1E97 ON action_action (action_target)');
        $this->addSql('CREATE TABLE action_action_group (action_id INT NOT NULL, action_group_id INT NOT NULL, PRIMARY KEY(action_id, action_group_id))');
        $this->addSql('CREATE INDEX IDX_167C5C599D32F035 ON action_action_group (action_id)');
        $this->addSql('CREATE INDEX IDX_167C5C592983A921 ON action_action_group (action_group_id)');
        $this->addSql('CREATE TABLE action_role (action_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(action_id, role_id))');
        $this->addSql('CREATE INDEX IDX_218831649D32F035 ON action_role (action_id)');
        $this->addSql('CREATE INDEX IDX_21883164D60322AC ON action_role (role_id)');
        $this->addSql('CREATE TABLE action.action_group (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, code VARCHAR(255) DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_711EF95877153098 ON action.action_group (code)');
        $this->addSql('CREATE INDEX IDX_711EF958727ACA70 ON action.action_group (parent_id)');
        $this->addSql('CREATE TABLE client_groups (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client_group_client (client_group_id INT NOT NULL, client_id INT NOT NULL, PRIMARY KEY(client_group_id, client_id))');
        $this->addSql('CREATE INDEX IDX_A5C87E84D0B2E982 ON client_group_client (client_group_id)');
        $this->addSql('CREATE INDEX IDX_A5C87E8419EB6921 ON client_group_client (client_id)');
        $this->addSql('CREATE TABLE groups (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, external_id VARCHAR(255) DEFAULT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F06D397077153098 ON groups (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F06D39709F75D7B0 ON groups (external_id)');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A4C98D39FE54D947 ON group_user (group_id)');
        $this->addSql('CREATE INDEX IDX_A4C98D39A76ED395 ON group_user (user_id)');
        $this->addSql('CREATE TABLE history_entity (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data JSON DEFAULT NULL, diff JSON DEFAULT NULL, comment TEXT DEFAULT NULL, user_user_id INT DEFAULT NULL, user_username VARCHAR(255) DEFAULT NULL, user_user_firstname VARCHAR(255) DEFAULT NULL, user_user_surname VARCHAR(255) DEFAULT NULL, user_user_patronymic VARCHAR(255) DEFAULT NULL, user_client_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX xaction ON history_entity (action)');
        $this->addSql('CREATE INDEX xloggedAt ON history_entity (logged_at)');
        $this->addSql('CREATE INDEX xobjectId ON history_entity (object_id)');
        $this->addSql('CREATE INDEX xobjectClass ON history_entity (object_class)');
        $this->addSql('CREATE INDEX xobjectMix ON history_entity (object_id, action, object_class)');
        $this->addSql('COMMENT ON COLUMN history_entity.data IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN history_entity.diff IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE icon (id SERIAL NOT NULL, class VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, code VARCHAR(255) DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_659429DB77153098 ON icon (code)');
        $this->addSql('CREATE TABLE import_export_file (id SERIAL NOT NULL, uploaded_file_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2E52CB1B276973A0 ON import_export_file (uploaded_file_id)');
        $this->addSql('CREATE TABLE notifications.notifications_list (id INT NOT NULL, type_id INT DEFAULT NULL, header VARCHAR(255) DEFAULT \'\' NOT NULL, data JSON NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_231FC8E2C54C8C93 ON notifications.notifications_list (type_id)');
        $this->addSql('CREATE TABLE notifications.notifications_to_send (id INT NOT NULL, notifications_list_id INT DEFAULT NULL, channel_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, value INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, viewed BOOLEAN DEFAULT \'false\', opened BOOLEAN DEFAULT \'false\', PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F3FACE77833BEE4 ON notifications.notifications_to_send (notifications_list_id)');
        $this->addSql('CREATE INDEX IDX_3F3FACE772F5A1AA ON notifications.notifications_to_send (channel_id)');
        $this->addSql('CREATE TABLE oauth_access_tokens (id SERIAL NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CA42527C5F37A13B ON oauth_access_tokens (token)');
        $this->addSql('CREATE INDEX IDX_CA42527C19EB6921 ON oauth_access_tokens (client_id)');
        $this->addSql('CREATE INDEX IDX_CA42527CA76ED395 ON oauth_access_tokens (user_id)');
        $this->addSql('CREATE TABLE oauth_auth_codes (id SERIAL NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri TEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BB493F835F37A13B ON oauth_auth_codes (token)');
        $this->addSql('CREATE INDEX IDX_BB493F8319EB6921 ON oauth_auth_codes (client_id)');
        $this->addSql('CREATE INDEX IDX_BB493F83A76ED395 ON oauth_auth_codes (user_id)');
        $this->addSql('CREATE TABLE oauth_clients (id SERIAL NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris TEXT NOT NULL, secret VARCHAR(255) NOT NULL, allowed_grant_types TEXT NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN oauth_clients.redirect_uris IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN oauth_clients.allowed_grant_types IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE oauth_refresh_tokens (id SERIAL NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5AB6875F37A13B ON oauth_refresh_tokens (token)');
        $this->addSql('CREATE INDEX IDX_5AB68719EB6921 ON oauth_refresh_tokens (client_id)');
        $this->addSql('CREATE INDEX IDX_5AB687A76ED395 ON oauth_refresh_tokens (user_id)');
        $this->addSql('CREATE TABLE print_form (id SERIAL NOT NULL, file_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, origin_file_name TEXT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8687BF0B93CB796C ON print_form (file_id)');
        $this->addSql('CREATE TABLE public.print_form_history (id SERIAL NOT NULL, user_id INT DEFAULT NULL, print_form TEXT NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D1EB9DA9A76ED395 ON public.print_form_history (user_id)');
        $this->addSql('CREATE TABLE reference.reference_countries (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_disease (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_file_types (id SERIAL NOT NULL, sort INT DEFAULT 0, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_measurement_units (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, sort INT DEFAULT 0, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_notifications_channel (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_notifications_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, template TEXT NOT NULL, required BOOLEAN DEFAULT \'false\', PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_tag_colors (id SERIAL NOT NULL, sort INT DEFAULT 0, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reference.reference_tag_forms (id SERIAL NOT NULL, sort INT DEFAULT 0, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE roles (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B63E2EC777153098 ON roles (code)');
        $this->addSql('CREATE INDEX IDX_B63E2EC7727ACA70 ON roles (parent_id)');
        $this->addSql('CREATE TABLE role_group (role_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(role_id, group_id))');
        $this->addSql('CREATE INDEX IDX_9A1CACEAD60322AC ON role_group (role_id)');
        $this->addSql('CREATE INDEX IDX_9A1CACEAFE54D947 ON role_group (group_id)');
        $this->addSql('CREATE TABLE role_client_group (role_id INT NOT NULL, client_group_id INT NOT NULL, PRIMARY KEY(role_id, client_group_id))');
        $this->addSql('CREATE INDEX IDX_5AFBB4AED60322AC ON role_client_group (role_id)');
        $this->addSql('CREATE INDEX IDX_5AFBB4AED0B2E982 ON role_client_group (client_group_id)');
        $this->addSql('CREATE TABLE settings (id SERIAL NOT NULL, key VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C58A90ABA9 ON settings (key)');
        $this->addSql('CREATE TABLE uploaded_file (id SERIAL NOT NULL, type VARCHAR(255) DEFAULT \'default\' NOT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, path TEXT DEFAULT NULL, md5 TEXT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, username VARCHAR(32) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, surname VARCHAR(255) DEFAULT \'\' NOT NULL, patronymic VARCHAR(255) DEFAULT \'\', salt VARCHAR(255) NOT NULL, confirmation_change_password_code VARCHAR(255) DEFAULT NULL, confirmation_change_password_recipient VARCHAR(255) DEFAULT NULL, confirmation_change_password_code_created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, additional_restrictions JSONB DEFAULT NULL, additional_fields JSONB DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, status BOOLEAN DEFAULT \'true\' NOT NULL, external_id VARCHAR(255) DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E99F75D7B0 ON users (external_id)');
        $this->addSql('COMMENT ON COLUMN users.additional_restrictions IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN users.additional_fields IS \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE action.action ADD CONSTRAINT FK_CFE36666727ACA70 FOREIGN KEY (parent_id) REFERENCES action.action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_action ADD CONSTRAINT FK_85FCBDF99DBA4E18 FOREIGN KEY (action_source) REFERENCES action.action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_action ADD CONSTRAINT FK_85FCBDF9845F1E97 FOREIGN KEY (action_target) REFERENCES action.action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_action_group ADD CONSTRAINT FK_167C5C599D32F035 FOREIGN KEY (action_id) REFERENCES action.action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_action_group ADD CONSTRAINT FK_167C5C592983A921 FOREIGN KEY (action_group_id) REFERENCES action.action_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_role ADD CONSTRAINT FK_218831649D32F035 FOREIGN KEY (action_id) REFERENCES action.action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_role ADD CONSTRAINT FK_21883164D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action.action_group ADD CONSTRAINT FK_711EF958727ACA70 FOREIGN KEY (parent_id) REFERENCES action.action_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_group_client ADD CONSTRAINT FK_A5C87E84D0B2E982 FOREIGN KEY (client_group_id) REFERENCES client_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_group_client ADD CONSTRAINT FK_A5C87E8419EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE import_export_file ADD CONSTRAINT FK_2E52CB1B276973A0 FOREIGN KEY (uploaded_file_id) REFERENCES uploaded_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notifications.notifications_list ADD CONSTRAINT FK_231FC8E2C54C8C93 FOREIGN KEY (type_id) REFERENCES reference.reference_notifications_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD CONSTRAINT FK_3F3FACE77833BEE4 FOREIGN KEY (notifications_list_id) REFERENCES notifications.notifications_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD CONSTRAINT FK_3F3FACE772F5A1AA FOREIGN KEY (channel_id) REFERENCES reference.reference_notifications_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_access_tokens ADD CONSTRAINT FK_CA42527C19EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_access_tokens ADD CONSTRAINT FK_CA42527CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_auth_codes ADD CONSTRAINT FK_BB493F8319EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_auth_codes ADD CONSTRAINT FK_BB493F83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_refresh_tokens ADD CONSTRAINT FK_5AB68719EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_refresh_tokens ADD CONSTRAINT FK_5AB687A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE print_form ADD CONSTRAINT FK_8687BF0B93CB796C FOREIGN KEY (file_id) REFERENCES uploaded_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.print_form_history ADD CONSTRAINT FK_D1EB9DA9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7727ACA70 FOREIGN KEY (parent_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_group ADD CONSTRAINT FK_9A1CACEAD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_group ADD CONSTRAINT FK_9A1CACEAFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_client_group ADD CONSTRAINT FK_5AFBB4AED60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_client_group ADD CONSTRAINT FK_5AFBB4AED0B2E982 FOREIGN KEY (client_group_id) REFERENCES client_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE action.action DROP CONSTRAINT FK_CFE36666727ACA70');
        $this->addSql('ALTER TABLE action_action DROP CONSTRAINT FK_85FCBDF99DBA4E18');
        $this->addSql('ALTER TABLE action_action DROP CONSTRAINT FK_85FCBDF9845F1E97');
        $this->addSql('ALTER TABLE action_action_group DROP CONSTRAINT FK_167C5C599D32F035');
        $this->addSql('ALTER TABLE action_role DROP CONSTRAINT FK_218831649D32F035');
        $this->addSql('ALTER TABLE action_action_group DROP CONSTRAINT FK_167C5C592983A921');
        $this->addSql('ALTER TABLE action.action_group DROP CONSTRAINT FK_711EF958727ACA70');
        $this->addSql('ALTER TABLE client_group_client DROP CONSTRAINT FK_A5C87E84D0B2E982');
        $this->addSql('ALTER TABLE role_client_group DROP CONSTRAINT FK_5AFBB4AED0B2E982');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT FK_A4C98D39FE54D947');
        $this->addSql('ALTER TABLE role_group DROP CONSTRAINT FK_9A1CACEAFE54D947');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP CONSTRAINT FK_3F3FACE77833BEE4');
        $this->addSql('ALTER TABLE client_group_client DROP CONSTRAINT FK_A5C87E8419EB6921');
        $this->addSql('ALTER TABLE oauth_access_tokens DROP CONSTRAINT FK_CA42527C19EB6921');
        $this->addSql('ALTER TABLE oauth_auth_codes DROP CONSTRAINT FK_BB493F8319EB6921');
        $this->addSql('ALTER TABLE oauth_refresh_tokens DROP CONSTRAINT FK_5AB68719EB6921');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP CONSTRAINT FK_3F3FACE772F5A1AA');
        $this->addSql('ALTER TABLE notifications.notifications_list DROP CONSTRAINT FK_231FC8E2C54C8C93');
        $this->addSql('ALTER TABLE action_role DROP CONSTRAINT FK_21883164D60322AC');
        $this->addSql('ALTER TABLE roles DROP CONSTRAINT FK_B63E2EC7727ACA70');
        $this->addSql('ALTER TABLE role_group DROP CONSTRAINT FK_9A1CACEAD60322AC');
        $this->addSql('ALTER TABLE role_client_group DROP CONSTRAINT FK_5AFBB4AED60322AC');
        $this->addSql('ALTER TABLE import_export_file DROP CONSTRAINT FK_2E52CB1B276973A0');
        $this->addSql('ALTER TABLE print_form DROP CONSTRAINT FK_8687BF0B93CB796C');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT FK_A4C98D39A76ED395');
        $this->addSql('ALTER TABLE oauth_access_tokens DROP CONSTRAINT FK_CA42527CA76ED395');
        $this->addSql('ALTER TABLE oauth_auth_codes DROP CONSTRAINT FK_BB493F83A76ED395');
        $this->addSql('ALTER TABLE oauth_refresh_tokens DROP CONSTRAINT FK_5AB687A76ED395');
        $this->addSql('ALTER TABLE public.print_form_history DROP CONSTRAINT FK_D1EB9DA9A76ED395');
        $this->addSql('DROP SEQUENCE history_entity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notifications.notifications_list_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notifications.notifications_to_send_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reference.reference_notifications_channel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reference.reference_notifications_type_id_seq CASCADE');
        $this->addSql('DROP TABLE action.action');
        $this->addSql('DROP TABLE action_action');
        $this->addSql('DROP TABLE action_action_group');
        $this->addSql('DROP TABLE action_role');
        $this->addSql('DROP TABLE action.action_group');
        $this->addSql('DROP TABLE client_groups');
        $this->addSql('DROP TABLE client_group_client');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE history_entity');
        $this->addSql('DROP TABLE icon');
        $this->addSql('DROP TABLE import_export_file');
        $this->addSql('DROP TABLE notifications.notifications_list');
        $this->addSql('DROP TABLE notifications.notifications_to_send');
        $this->addSql('DROP TABLE oauth_access_tokens');
        $this->addSql('DROP TABLE oauth_auth_codes');
        $this->addSql('DROP TABLE oauth_clients');
        $this->addSql('DROP TABLE oauth_refresh_tokens');
        $this->addSql('DROP TABLE print_form');
        $this->addSql('DROP TABLE public.print_form_history');
        $this->addSql('DROP TABLE reference.reference_countries');
        $this->addSql('DROP TABLE reference.reference_disease');
        $this->addSql('DROP TABLE reference.reference_file_types');
        $this->addSql('DROP TABLE reference.reference_measurement_units');
        $this->addSql('DROP TABLE reference.reference_notifications_channel');
        $this->addSql('DROP TABLE reference.reference_notifications_type');
        $this->addSql('DROP TABLE reference.reference_tag_colors');
        $this->addSql('DROP TABLE reference.reference_tag_forms');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE role_group');
        $this->addSql('DROP TABLE role_client_group');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE uploaded_file');
        $this->addSql('DROP TABLE users');
    }
}
