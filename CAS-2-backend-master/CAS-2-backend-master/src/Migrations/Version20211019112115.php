<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019112115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET url='/pet-common' WHERE id=(select id from action.action where code='pets-common')");
        $this->addSql("UPDATE action.action SET url='/pet-living' WHERE id=(select id from action.action where code='pets-living')");
        $this->addSql("UPDATE action.action SET url='/pet-stamp' WHERE id=(select id from action.action where code='pets-stamps')");
        $this->addSql("UPDATE action.action SET url='/pet-breed' WHERE id=(select id from action.action where code='pets-breed')");
        $this->addSql("UPDATE action.action SET url='/station' WHERE id=(select id from action.action where code='station')");
        $this->addSql("UPDATE action.action SET url='/supervised-objects' WHERE id=(select id from action.action where code='supervised-objects')");
        $this->addSql("UPDATE action.action SET url='/business-entity' WHERE id=(select id from action.action where code='business-entity')");
        $this->addSql("UPDATE action.action SET url='/vaccine-manufacturer' WHERE id=(select id from action.action where code='vaccine-manufacturer')");
        $this->addSql("UPDATE action.action SET url='/vaccine-common' WHERE id=(select id from action.action where code='vaccine-common')");
        $this->addSql("UPDATE action.action SET url='/vaccine-disease' WHERE id=(select id from action.action where code='vaccine-disease')");
        $this->addSql("UPDATE action.action SET url='/vaccine-series' WHERE id=(select id from action.action where code='vaccine-series')");
        $this->addSql("UPDATE action.action SET url='/common-countries' WHERE id=(select id from action.action where code='countries')");
        $this->addSql("UPDATE action.action SET url='/common-measurement-units' WHERE id=(select id from action.action where code='measurement-units')");
        $this->addSql("UPDATE action.action SET url='/notifications-channel' WHERE id=(select id from action.action where code='notifications-channel')");
        $this->addSql("UPDATE action.action SET url='/notifications-type' WHERE id=(select id from action.action where code='notifications-type')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET url='/reference/pet-common' WHERE id=(select id from action.action where code='pets-common')");
        $this->addSql("UPDATE action.action SET url='/reference/pet-living' WHERE id=(select id from action.action where code='pets-living')");
        $this->addSql("UPDATE action.action SET url='/reference/pet-stamp' WHERE id=(select id from action.action where code='pets-stamps')");
        $this->addSql("UPDATE action.action SET url='/reference/pet-breed' WHERE id=(select id from action.action where code='pets-breed')");
        $this->addSql("UPDATE action.action SET url='/reference/station' WHERE id=(select id from action.action where code='station')");
        $this->addSql("UPDATE action.action SET url='/reference/supervised-objects' WHERE id=(select id from action.action where code='supervised-objects')");
        $this->addSql("UPDATE action.action SET url='/reference/business-entity' WHERE id=(select id from action.action where code='business-entity')");
        $this->addSql("UPDATE action.action SET url='/reference/vaccine-manufacturer' WHERE id=(select id from action.action where code='vaccine-manufacturer')");
        $this->addSql("UPDATE action.action SET url='/reference/vaccine-common' WHERE id=(select id from action.action where code='vaccine-common')");
        $this->addSql("UPDATE action.action SET url='/reference/vaccine-disease' WHERE id=(select id from action.action where code='vaccine-disease')");
        $this->addSql("UPDATE action.action SET url='/reference/vaccine-series' WHERE id=(select id from action.action where code='vaccine-series')");
        $this->addSql("UPDATE action.action SET url='/reference/vaccine-series' WHERE id=(select id from action.action where code='vaccine-series')");
        $this->addSql("UPDATE action.action SET url='/reference/common-countries' WHERE id=(select id from action.action where code='countries')");
        $this->addSql("UPDATE action.action SET url='/reference/common-measurement-units' WHERE id=(select id from action.action where code='measurement-units')");
        $this->addSql("UPDATE action.action SET url='/reference/notifications-channel' WHERE id=(select id from action.action where code='notifications-channel')");
        $this->addSql("UPDATE action.action SET url='/reference/notifications-type' WHERE id=(select id from action.action where code='notifications-type')");
    }
}
