<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402114820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.reference_nomenclature (id SERIAL NOT NULL, description TEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reference-nomenclature', true, 0, false, false, false, 'Номенклатура', 'reference-nomenclature', false, 'Номенклатура', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Nomenclature', 'Номенклатура');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Nomenclature'), 3);");
        $this->addSql("CREATE TRIGGER reference_nomenclature_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_nomenclature FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reference-nomenclature');");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('Производитель', '/admin/references/reference-manufacturer', 'Справочник производителей')");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('Форма выпуска', '/admin/references/reference-release-form', 'Справочник форма выпуска')");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('Категории номенклатуры', '/admin/references/reference-category-nomenclature', 'Справочник категорий номенклатуры')");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('Страны', '/admin/references/reference-countries', 'Справочник стран')");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('Единицы измерения', '/admin/references/reference-measurement-units', 'Справочник единиц измерения')");
        $this->addSql("INSERT INTO action.action_group (id, name, code, parent_id ) VALUES ((select max(id)+1 from action.action_group), 'Номенклатура', 'nomenclature', 3)");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='Производитель')");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='Форма выпуска')");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='Категории номенклатуры')");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='Страны')");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='Единицы измерения')");
        $this->addSql("SELECT SETVAL('reference.reference_nomenclature_id_seq', (select max(id) from reference.reference_nomenclature));");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE IF EXISTS reference.reference_nomenclature');
        $this->addSql("DROP TRIGGER IF EXISTS reference_nomenclature_items_count on reference.reference_nomenclature ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Nomenclature');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Nomenclature');");
        $this->addSql("DELETE FROM action.action_group where code='nomenclature';");
    }
}
