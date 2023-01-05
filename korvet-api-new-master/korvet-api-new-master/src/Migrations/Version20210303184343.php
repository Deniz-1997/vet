<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303184343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE notifications.notifications_list DROP CONSTRAINT fk_231fc8e272f5a1aa');
        $this->addSql('DROP INDEX IF EXISTS idx_231fc8e272f5a1aa');
        $this->addSql('ALTER TABLE notifications.notifications_list DROP channel_id');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD channel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD CONSTRAINT FK_3F3FACE772F5A1AA FOREIGN KEY (channel_id) REFERENCES reference.reference_notifications_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3F3FACE772F5A1AA ON notifications.notifications_to_send (channel_id)');

        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD sended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE notifications.notifications_list ADD channel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications.notifications_list ADD date_send TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications.notifications_list ADD CONSTRAINT fk_231fc8e272f5a1aa FOREIGN KEY (channel_id) REFERENCES reference.reference_notifications_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_231fc8e272f5a1aa ON notifications.notifications_list (channel_id)');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP CONSTRAINT FK_3F3FACE772F5A1AA');
        $this->addSql('DROP INDEX IDX_3F3FACE772F5A1AA');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP channel_id');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP created_at');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP sended_at');
    }
}
