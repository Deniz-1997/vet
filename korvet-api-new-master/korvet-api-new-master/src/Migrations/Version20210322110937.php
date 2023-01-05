<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322110937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('CREATE TABLE reference.reference_reason_retiring (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reason-retiring', true, 0, false, false, false, 'Причина выбытия животного', 'reason-retiring', false, 'Причина выбытия животного', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\ReasonRetiring', 'Причина выбытия');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\ReasonRetiring'), 3);");
        $this->addSql("CREATE TRIGGER reference_reason_retiring_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_reason_retiring FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reason-retiring');");



        $this->addSql('CREATE SEQUENCE pet.pet_index_search_id_seq');
        $this->addSql('SELECT setval(\'pet.pet_index_search_id_seq\', (SELECT MAX(id) FROM pet.pet_index_search))');
        $this->addSql('ALTER TABLE pet.pet_index_search ALTER id SET DEFAULT nextval(\'pet.pet_index_search_id_seq\')');
        $this->addSql('ALTER INDEX pet.uniq_49e63f07966f7fb6 RENAME TO UNIQ_9D0B2A43966F7FB6');
        $this->addSql('ALTER TABLE pet.pets ADD pet_retiring_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pet.pets ADD is_retiring BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE pet.pets ADD date_of_retiring TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE pet.pets ADD CONSTRAINT FK_59C2C62A53C7630B FOREIGN KEY (pet_retiring_id) REFERENCES reference.reference_reason_retiring (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_59C2C62A53C7630B ON pet.pets (pet_retiring_id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql("DROP TRIGGER IF EXISTS reference_reason_retiring_items_count on reference.reference_reason_retiring ");
        $this->addSql('DROP TABLE reference.reference_reason_retiring');
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\ReasonRetiring');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\ReasonRetiring');");

        $this->addSql('ALTER TABLE pet_index_search ADD CONSTRAINT fk_49e63f07966f7fb6 FOREIGN KEY (pet_id) REFERENCES pet.pets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pet.pet_index_search ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE pet.pets DROP CONSTRAINT FK_59C2C62A53C7630B');
        $this->addSql('DROP INDEX IDX_59C2C62A53C7630B');
        $this->addSql('ALTER TABLE pet.pets DROP pet_retiring_id');
        $this->addSql('ALTER TABLE pet.pets DROP is_retiring');
        $this->addSql('ALTER TABLE pet.pets DROP date_of_retiring');

    }
}
