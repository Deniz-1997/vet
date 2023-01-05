<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220318131225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SELECT setval(\'action.action_id_seq\', (SELECT MAX(id) FROM action.action))');
        $this->addSql('SELECT setval(\'action.action_group_id_seq\', (SELECT MAX(id) FROM action.action_group))');
    }

    public function down(Schema $schema): void
    {

    }
}
