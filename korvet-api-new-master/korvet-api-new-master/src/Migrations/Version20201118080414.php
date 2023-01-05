<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118080414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE reference_pet_aggressive_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reference_pet_aggressive_types (id INT NOT NULL, name VARCHAR(255) NOT NULL, deleted BOOLEAN NOT NULL DEFAULT FALSE, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action), 'ENTITY_LIST_URL', '/admin/references/pet-aggressive-type', true, 0, false, false, false, 'Степень агрессивности животного', 'pet-aggressive-type', false, 'Список степеней агрессивности животного', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Pet\AggresiveType', 'Степень агрессивности');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action WHERE entity_class_name = 'App\Entity\Reference\Pet\AggresiveType'), 3);");
        $this->addSql('ALTER TABLE pets ADD aggressive_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pets ADD CONSTRAINT FK_8638EA3F181F0D72 FOREIGN KEY (aggressive_type_id) REFERENCES reference_pet_aggressive_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8638EA3F181F0D72 ON pets (aggressive_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE reference_pet_aggressive_types_id_seq CASCADE');
        $this->addSql('DROP TABLE reference_pet_aggressive_types');
        $this->addSql("DELETE FROM action_action_group WHERE id = (SELECT id FROM action WHERE entity_class_name = 'App\Entity\Reference\Pet\AggresiveType');");
        $this->addSql("DELETE FROM action WHERE id = (SELECT id FROM action WHERE entity_class_name = 'App\Entity\Reference\Pet\AggresiveType');");
        $this->addSql('ALTER TABLE pets DROP CONSTRAINT FK_8638EA3F181F0D72');
        $this->addSql('DROP INDEX IDX_8638EA3F181F0D72');
        $this->addSql('ALTER TABLE pets DROP aggressive_type_id');
    }
}
