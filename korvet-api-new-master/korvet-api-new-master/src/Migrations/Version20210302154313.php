<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302154313 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA notifications');
        $this->addSql('CREATE SEQUENCE notifications.notifications_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notifications.notifications_to_send_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notifications.notifications_list (id INT NOT NULL, type_id INT DEFAULT NULL, channel_id INT DEFAULT NULL, viewed BOOLEAN DEFAULT \'false\', opened BOOLEAN DEFAULT \'false\', data JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_231FC8E2C54C8C93 ON notifications.notifications_list (type_id)');
        $this->addSql('CREATE INDEX IDX_231FC8E272F5A1AA ON notifications.notifications_list (channel_id)');
        $this->addSql('CREATE TABLE notifications.notifications_to_send (id INT NOT NULL, notifications_list_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F3FACE77833BEE4 ON notifications.notifications_to_send (notifications_list_id)');
        $this->addSql('ALTER TABLE notifications.notifications_list ADD CONSTRAINT FK_231FC8E2C54C8C93 FOREIGN KEY (type_id) REFERENCES reference.reference_notifications_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notifications.notifications_list ADD CONSTRAINT FK_231FC8E272F5A1AA FOREIGN KEY (channel_id) REFERENCES reference.reference_notifications_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD CONSTRAINT FK_3F3FACE77833BEE4 FOREIGN KEY (notifications_list_id) REFERENCES notifications.notifications_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP CONSTRAINT FK_3F3FACE77833BEE4');
        $this->addSql('DROP SEQUENCE notifications.notifications_list_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notifications.notifications_to_send_id_seq CASCADE');
        $this->addSql('DROP TABLE notifications.notifications_list');
        $this->addSql('DROP TABLE notifications.notifications_to_send');
        $this->addSql('DROP SCHEMA notifications');
    }
}
