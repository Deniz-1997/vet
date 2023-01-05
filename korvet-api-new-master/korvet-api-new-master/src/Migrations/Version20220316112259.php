<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316112259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('COMMENT ON COLUMN history_entity.data IS null');
        $this->addSql('COMMENT ON COLUMN history_entity.diff IS null');
        $this->addSql('COMMENT ON COLUMN users.additional_restrictions IS null');
        $this->addSql('COMMENT ON COLUMN users.additional_fields IS null');
        $this->addSql('COMMENT ON COLUMN ftp_history.report IS null');
        $this->addSql('COMMENT ON COLUMN appointment.appointment_template.products IS null');

    }

    public function down(Schema $schema): void
    {

    }
}
