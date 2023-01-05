<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309095709 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE notifications.notifications_list DROP viewed');
        $this->addSql('ALTER TABLE notifications.notifications_list DROP opened');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD viewed BOOLEAN DEFAULT \'false\'');
        $this->addSql('ALTER TABLE notifications.notifications_to_send ADD opened BOOLEAN DEFAULT \'false\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE notifications.notifications_list ADD viewed BOOLEAN DEFAULT \'false\'');
        $this->addSql('ALTER TABLE notifications.notifications_list ADD opened BOOLEAN DEFAULT \'false\'');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP viewed');
        $this->addSql('ALTER TABLE notifications.notifications_to_send DROP opened');
    }
}
