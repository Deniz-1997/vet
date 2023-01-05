<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Reference\MeasurementUnits;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401093924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.reference_measurement_units (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, sort INT DEFAULT 0, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE product.product ADD measurement_units_id INT DEFAULT NULL');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reference-measurement-units', true, 0, false, false, false, 'Единицы измерения', 'reference-measurement-units', false, 'Единицы измерения', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\MeasurementUnits', 'Единицы измерения');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\MeasurementUnits'), 3);");
        $this->addSql("CREATE TRIGGER reference_measurement_units_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_measurement_units FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reference-measurement-units');");
        $this->addSql("INSERT INTO reference.reference_measurement_units (name) SELECT DISTINCT measure FROM product.product WHERE measure IS NOT NULL AND measure <>''");
        $this->addSql("SELECT SETVAL('reference.reference_measurement_units_id_seq', (select max(id) from reference.reference_measurement_units));");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE reference.reference_measurement_units');
        $this->addSql('ALTER TABLE product.product DROP measurement_units_id');
        $this->addSql("DROP TRIGGER IF EXISTS reference_measurement_units_items_count on reference.reference_measurement_units ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\MeasurementUnits');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\MeasurementUnits');");

    }
}
