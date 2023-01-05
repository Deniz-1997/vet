<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329134427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE product.product p SET countries_id = (SELECT id FROM reference.reference_countries WHERE LOWER(name)=LOWER(p.country)) WHERE country IS NOT NULL AND country <> ''");
        $this->addSql('ALTER TABLE product.product DROP COLUMN country');

    }

    public function down(Schema $schema) : void
    {

    }
}
