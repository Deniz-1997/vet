<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926134311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE structure.supervised_objects (id uuid not null default uuid_generate_v4(), user_id INT DEFAULT NULL, station_id INT DEFAULT NULL, legal_forms VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, latitude INT DEFAULT NULL, longitude INT DEFAULT NULL, kpp VARCHAR(255) DEFAULT NULL, head_full_name VARCHAR(255) DEFAULT NULL, head_office VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, activity_kind VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, telephone_number VARCHAR(255) DEFAULT NULL, registration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, liquidation_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, internet_connection BOOLEAN DEFAULT \'false\', issues_certificates BOOLEAN DEFAULT \'false\', pushing_available BOOLEAN DEFAULT \'false\', compartment INT DEFAULT 0, name VARCHAR(255) DEFAULT \'\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, businessEntity_id uuid not null default uuid_generate_v4(), PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8E4742A76ED395 ON structure.supervised_objects (user_id)');
        $this->addSql('CREATE INDEX IDX_D8E474221BDB235 ON structure.supervised_objects (station_id)');
        $this->addSql('CREATE INDEX IDX_D8E47422524B408 ON structure.supervised_objects (businessEntity_id)');
        $this->addSql('COMMENT ON COLUMN structure.supervised_objects.legal_forms IS \'(DC2Type:App\\Packages\\DBAL\\Types\\LegalFormsEnum)\'');
        $this->addSql('ALTER TABLE structure.supervised_objects ADD CONSTRAINT FK_D8E4742A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE structure.supervised_objects ADD CONSTRAINT FK_D8E474221BDB235 FOREIGN KEY (station_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE structure.supervised_objects ADD CONSTRAINT FK_D8E47422524B408 FOREIGN KEY (businessEntity_id) REFERENCES structure.busines_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'URL', '/reference/supervised-objects', false, 0, false, false, false, 'Поднадзорные объекты', 'supervisedObjects', false, 'Пункт меню', (select id from action.action where name='Справочники'), 520, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('DROP TABLE structure.supervised_objects');
        $this->addSql('ALTER TABLE structure.busines_entity ALTER id SET DEFAULT \'uuid_generate_v4()\'');
    }
}
