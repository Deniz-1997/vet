<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412112403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET parent_id = (select id from action.action where code='store'), name='Справочники номенклатуры', url='/store/reference-nomenclature' WHERE code = 'reference-nomenclature';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-manufacturer' WHERE url like '%reference-manufacturer';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-release-form' WHERE url like '%reference-release-form';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-category-nomenclature' WHERE url like '%reference-category-nomenclature';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-countries' WHERE url like '%reference-countries';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-measurement-units' WHERE url like '%reference-measurement-units';");

        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_MENU_REFERENCE_NOMANCLATURE', 'Пункт меню - Справочники номенклатуры', null) ON CONFLICT DO NOTHING");
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET parent_id = (select id from action.action where code='references'), url='/admin/references/reference-nomenclature' WHERE code = 'reference-nomenclature';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/admin/references/reference-manufacturer' WHERE url like '%reference-manufacturer';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/admin/references/reference-release-form' WHERE url like '%reference-release-form';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/admin/references/reference-category-nomenclature' WHERE url like '%reference-category-nomenclature';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/admin/references/reference-countries' WHERE url like '%reference-countries';");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/admin/references/reference-measurement-units' WHERE url like '%reference-measurement-units';");

        $this->addSql("DELETE FROM roles WHERE code IN ('ROLE_MENU_REFERENCE_NOMANCLATURE')");
    }
}
