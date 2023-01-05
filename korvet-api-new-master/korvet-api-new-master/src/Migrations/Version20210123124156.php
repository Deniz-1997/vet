<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210123124156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE public.webslon_settings RENAME TO settings');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C58A90ABA9 ON settings (key)');
        $this->addSql('DROP SEQUENCE webslon_settings_id_seq CASCADE');
        $this->addSql('DROP INDEX IF EXISTS settings_id_uindex');
        $this->addSql('DROP INDEX IF EXISTS uniq_d9c626208a90aba9');
        $this->addSql('CREATE SEQUENCE settings_id_seq');
        $this->addSql('SELECT setval(\'settings_id_seq\', (SELECT MAX(id) FROM settings))');
        $this->addSql('ALTER TABLE settings ALTER id SET DEFAULT nextval(\'settings_id_seq\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE public.settings RENAME TO webslon_settings');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C58A90ABA9 ON webslon_settings (key)');
        $this->addSql('CREATE SEQUENCE webslon_settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE settings ALTER id DROP DEFAULT');
        $this->addSql('CREATE UNIQUE INDEX settings_id_uindex ON settings (id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_d9c626208a90aba9 ON settings (key)');
    }
}
