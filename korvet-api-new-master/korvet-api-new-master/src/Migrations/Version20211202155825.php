<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202155825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        //$this->addSql("DROP SEQUENCE public.reference_owner_files_id_seq CASCADE;");
        $this->addSql("CREATE SEQUENCE reference.reference_owner_files_id_seq INCREMENT BY 1 MINVALUE 1;");
        $this->addSql("ALTER TABLE reference.reference_owner_files ALTER COLUMN id SET DEFAULT nextval('reference.reference_owner_files_id_seq'::regclass);");
        $this->addSql("SELECT SETVAL('reference.reference_owner_files_id_seq', (select max(id) from reference.reference_owner_files));");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DROP SEQUENCE reference.reference_owner_files_id_seq CASCADE;");
    }
}
