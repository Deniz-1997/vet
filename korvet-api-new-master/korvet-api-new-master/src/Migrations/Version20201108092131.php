<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108092131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE appointment_logs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE appointment_logs (id INT NOT NULL, user_id INT DEFAULT NULL, status_id INT DEFAULT NULL, appointment_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E34C0E9DA76ED395 ON appointment_logs (user_id)');
        $this->addSql('CREATE INDEX IDX_E34C0E9D6BF700BD ON appointment_logs (status_id)');
        $this->addSql('CREATE INDEX IDX_E34C0E9DE5B533F9 ON appointment_logs (appointment_id)');
        $this->addSql('ALTER TABLE appointment_logs ADD CONSTRAINT FK_E34C0E9DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_logs ADD CONSTRAINT FK_E34C0E9D6BF700BD FOREIGN KEY (status_id) REFERENCES appointment_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_logs ADD CONSTRAINT FK_E34C0E9DE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE appointment_logs_id_seq CASCADE');
        $this->addSql('CREATE TABLE product_stock_logs (product_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'now()\', count_add INT DEFAULT NULL, stock_id INT DEFAULT NULL)');
        $this->addSql('DROP TABLE appointment_logs');
    }
}
