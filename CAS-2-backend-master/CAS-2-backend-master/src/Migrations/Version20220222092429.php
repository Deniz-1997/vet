<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222092429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE api_token (id SERIAL NOT NULL, access_token VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) NOT NULL, scope VARCHAR(255) DEFAULT NULL, expired_in TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, token_type VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN api_token.token_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\TokenTypeEnum)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE api_token');
    }
}
