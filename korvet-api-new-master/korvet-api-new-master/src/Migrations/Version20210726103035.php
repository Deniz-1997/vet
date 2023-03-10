<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726103035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('CREATE TABLE reference.reference_disease (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_disease (product_id INT NOT NULL, disease_id INT NOT NULL, PRIMARY KEY(product_id, disease_id))');
        $this->addSql('CREATE INDEX IDX_DEE271714584665A ON product_disease (product_id)');
        $this->addSql('CREATE INDEX IDX_DEE27171D8355341 ON product_disease (disease_id)');
        $this->addSql('ALTER TABLE product_disease ADD CONSTRAINT FK_DEE271714584665A FOREIGN KEY (product_id) REFERENCES product.product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_disease ADD CONSTRAINT FK_DEE27171D8355341 FOREIGN KEY (disease_id) REFERENCES reference.reference_disease (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reference-disease', true, 0, false, false, false, '??????????????????????', 'reference-disease', false, '??????????????????????', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Disease', '??????????????????????');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Disease'), 3);");
        $this->addSql("CREATE TRIGGER reference_disease_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_disease FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reference-countries');");
        $this->addSql("SELECT SETVAL('reference.reference_disease_id_seq', (select max(id) from reference.reference_disease));");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????-????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????? ???????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????? ???????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??. ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ?????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ?????????????????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ?????????????????????????????? ????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ?????????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ??????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????-??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????? ?????????????????????????? ???????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ?????????????????????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ?????????????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ?????????????????? ???????????????? ???????????????? ???????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ???????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ???????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ?????????????????????????????? ??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????-?????????????????? ??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ???????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ???????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????????? (??????????????)')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????? ?????????????????? (??????????????)')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ???????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ?????????????????????????????? ???????????????? ???????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ?????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????-??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ???????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????? ?????????????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????? ?????????????????????????? ???????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????-????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????? ???????? ?? ??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ?????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????-3')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????? ?????????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????-?????????????????????????? ?????????????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????-?????????????????????????? ???????????????? ??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ?????????????????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????? ????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ???????????????? ????????????????????????-76')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????-76')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????-???????? (????????????????????????)')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????-????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????? ???????????????? ???????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????? ???????????? ??????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????? ????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????????????? ??????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('???????????????????????????? ????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('?????????????????? ??????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('??????????????????????')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('????????')");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-disease' WHERE url like '%reference-disease';");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('??????????????????????', '/admin/references/reference-disease', '???????????????????? ??????????????????????')");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='??????????????????????')");



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product.product DROP CONSTRAINT FK_62698E13D8355341');
        $this->addSql('DROP TABLE reference.reference_disase');
        $this->addSql('DROP INDEX IDX_62698E13D8355341');
        $this->addSql('DROP TABLE product_disease');
        $this->addSql("DROP TRIGGER IF EXISTS reference_disease_items_count on reference.reference_disease ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Disease');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Disease');");
    }
}
