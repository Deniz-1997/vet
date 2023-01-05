<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427070858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.subdivision (id SERIAL NOT NULL, station_id INT NOT NULL, is_private BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, external_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_425F75329F75D7B0 ON reference.subdivision (external_id)');
        $this->addSql('CREATE INDEX IDX_425F753221BDB235 ON reference.subdivision (station_id)');
        $this->addSql('ALTER TABLE reference.subdivision ADD CONSTRAINT FK_425F753221BDB235 FOREIGN KEY (station_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) 
        VALUES ((select max(id)+1 from action.action), 'URL', '/subdivision', false, 0, false, false, false, 'Подразделения', 'subdivision', false, 'Пункт меню', (select id from action.action where name='Организации'), 600, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE reference.subdivision');

        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE code = 'subdivision');");
    }
}
