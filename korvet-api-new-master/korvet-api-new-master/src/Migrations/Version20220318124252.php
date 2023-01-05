<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220318124252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('create sequence reference.reference_profession_id_seq as integer');
        $this->addSql('alter table reference.reference_profession alter column id set default nextval(\'reference.reference_profession_id_seq\'::regclass)');
        $this->addSql('alter sequence reference.reference_profession_id_seq owned by reference.reference_profession.id');
        $this->addSql('SELECT setval(\'reference.reference_profession_id_seq\', (SELECT MAX(id) FROM reference.reference_profession))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop sequence reference.reference_profession_id_seq cascade');
    }
}
