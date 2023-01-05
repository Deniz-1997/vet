<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200124082744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {

        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_BUTTON_APPOINTMENT_REGISTER', 'Кнопка - Зарегистрировать прием') ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (parent_id, code, name) VALUES (NULL, 'ROLE_BUTTON_DOCUMENT_EDIT', 'Кнопка - Редактировать документ') ON CONFLICT DO NOTHING");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
