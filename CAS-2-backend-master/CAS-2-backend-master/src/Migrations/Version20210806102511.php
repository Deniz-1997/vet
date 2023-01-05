<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806102511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO Settings (id, key, value) values (1, 'contact.email', 'support@mart-info.ru')");
        $this->addSql("INSERT INTO Settings (id, key, value) values ((select max(id)+1 from Settings), 'contact.phone_number', '+7 495 120-10-53')");
        $this->addSql("INSERT INTO Settings (id, key, value) values ((select max(id)+1 from Settings), 'domain.code', '50')");
        $this->addSql("INSERT INTO Settings (id, key, value) values ((select max(id)+1 from Settings), 'contact.signature', '©2021 «CAS»')");
        $this->addSql("INSERT INTO Settings (id, key, value) values ((select max(id)+1 from Settings), 'contact.global_phone_number', ' 8 800 250-17-81')");
       
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM Settings');
    }
}
