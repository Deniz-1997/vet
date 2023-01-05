<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924090454 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA structure');
        $this->addSql('CREATE TABLE structure.busines_entity (id uuid not null default uuid_generate_v4(), user_id INT NOT NULL, legal_forms VARCHAR(255) NOT NULL, legal_addres VARCHAR(255) DEFAULT NULL, kpp VARCHAR(255) DEFAULT NULL, ogrn VARCHAR(255) DEFAULT NULL, inn VARCHAR(255) DEFAULT NULL, bik VARCHAR(255) DEFAULT NULL, head_full_name VARCHAR(255) DEFAULT NULL, head_office VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, registration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, liquidation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, plan_month INT DEFAULT NULL, plan_skip_year INT DEFAULT NULL, checking_account VARCHAR(255) DEFAULT NULL, cor_account VARCHAR(255) DEFAULT NULL, business_size VARCHAR(255) DEFAULT NULL, working_with_social_obj BOOLEAN DEFAULT \'false\', last_check INT DEFAULT NULL, risk_points INT DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3CF4CAEA76ED395 ON structure.busines_entity (user_id)');
        $this->addSql('ALTER TABLE structure.busines_entity ADD CONSTRAINT FK_B3CF4CAEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('COMMENT ON COLUMN structure.busines_entity.legal_forms IS \'(DC2Type:App\\Packages\\DBAL\\Types\\LegalFormsEnum)\'');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/businessEntity', false, 0, false, false, false, 'Хоз. субъекты', 'businessEntity', false, 'Пункт меню', (select id from action.action where name='Справочники'), 520, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
          $this->addSql('DROP TABLE structure.busines_entity');
    }
}
